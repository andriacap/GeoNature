import pytest
import uuid

from flask import url_for, current_app
from werkzeug.exceptions import Unauthorized, Forbidden, Conflict, BadRequest, NotFound
from sqlalchemy import func

from geonature.utils.env import db
from geonature.core.gn_meta.models import TDatasets, TAcquisitionFramework
from geonature.core.gn_meta.routes import get_af_from_id

from pypnusershub.db.tools import user_to_token

from .fixtures import acquisition_frameworks, datasets, synthese_data
from .utils import set_logged_user_cookie, logged_user_headers


@pytest.fixture
def af_list():
    return  [
                {
                    "id_acquisition_framework": 5
                },
                {
                    "id_acquisition_framework": 2
                },
                {
                    "id_acquisition_framework": 1
                },
            ]


@pytest.mark.usefixtures("client_class", "temporary_transaction")
class TestGNMeta:
    def test_acquisition_frameworks_permissions(self, app, acquisition_frameworks, datasets, users):
        af = acquisition_frameworks['own_af']
        with app.test_request_context(headers=logged_user_headers(users['user'])):
            app.preprocess_request()
            assert af.has_instance_permission(0) == False
            assert af.has_instance_permission(1) == True
            assert af.has_instance_permission(2) == True
            assert af.has_instance_permission(3) == True

        with app.test_request_context(headers=logged_user_headers(users['associate_user'])):
            app.preprocess_request()
            assert af.has_instance_permission(0) == False
            assert af.has_instance_permission(1) == False
            assert af.has_instance_permission(2) == True
            assert af.has_instance_permission(3) == True

        with app.test_request_context(headers=logged_user_headers(users['stranger_user'])):
            app.preprocess_request()
            assert af.has_instance_permission(0) == False
            assert af.has_instance_permission(1) == False
            assert af.has_instance_permission(2) == False
            assert af.has_instance_permission(3) == True

        af = acquisition_frameworks['orphan_af']  # all DS are attached to this AF
        with app.test_request_context(headers=logged_user_headers(users['user'])):
            app.preprocess_request()
            assert af.has_instance_permission(0) == False
            # The AF has no actors, but the AF has DS on which the user is digitizer!
            assert af.has_instance_permission(1) == True
            assert af.has_instance_permission(2) == True
            assert af.has_instance_permission(3) == True

            nested = db.session.begin_nested()
            af.t_datasets.remove(datasets['own_dataset'])
            # Now, the AF has no DS on which user is digitizer.
            assert af.has_instance_permission(1) == False
            # But the AF has still DS on which user organism is actor.
            assert af.has_instance_permission(2) == True
            nested.rollback()
            assert datasets['own_dataset'] in af.t_datasets

        with app.test_request_context(headers=logged_user_headers(users['user'])):
            app.preprocess_request()
            af_ids = [ af.id_acquisition_framework for af in acquisition_frameworks.values() ]
            qs = TAcquisitionFramework.query.filter(
                    TAcquisitionFramework.id_acquisition_framework.in_(af_ids)
            )
            assert set(qs.filter_by_scope(0).all()) == set([])
            assert set(qs.filter_by_scope(1).all()) == set([
                acquisition_frameworks['own_af'],
                acquisition_frameworks['orphan_af'],  # through DS
            ])
            assert set(qs.filter_by_scope(2).all()) == set([
                acquisition_frameworks['own_af'],
                acquisition_frameworks['associate_af'],
                acquisition_frameworks['orphan_af'],  # through DS
            ])
            assert set(qs.filter_by_scope(3).all()) == set(acquisition_frameworks.values())

    def test_acquisition_framework_is_deletable(self, app, acquisition_frameworks, datasets):
        assert acquisition_frameworks['own_af'].is_deletable() == True
        assert acquisition_frameworks['orphan_af'].is_deletable() == False  # DS are attached to this AF

    def test_delete_acquisition_framework(self, app, users, acquisition_frameworks, datasets):
        af_id = acquisition_frameworks['orphan_af'].id_acquisition_framework

        response = self.client.delete(url_for("gn_meta.delete_acquisition_framework", af_id=af_id))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['noright_user'])

        # The user has no rights on METADATA module
        response = self.client.delete(url_for("gn_meta.delete_acquisition_framework", af_id=af_id))
        assert response.status_code == Forbidden.code
        assert 'METADATA' in response.json['description']

        set_logged_user_cookie(self.client, users['self_user'])

        # The user has right on METADATA module, but not on this specific AF
        response = self.client.delete(url_for("gn_meta.delete_acquisition_framework", af_id=af_id))
        assert response.status_code == Forbidden.code
        assert 'METADATA' not in response.json['description']

        set_logged_user_cookie(self.client, users['admin_user'])

        # The AF can not be deleted due to attached DS
        response = self.client.delete(url_for("gn_meta.delete_acquisition_framework", af_id=af_id))
        assert response.status_code == Conflict.code

        set_logged_user_cookie(self.client, users['user'])
        af_id = acquisition_frameworks['own_af'].id_acquisition_framework

        response = self.client.delete(url_for("gn_meta.delete_acquisition_framework", af_id=af_id))
        assert response.status_code == 204

    def test_get_acquisition_frameworks(self, users):
        response = self.client.get(url_for("gn_meta.get_acquisition_frameworks"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['admin_user'])

        response = self.client.get(url_for("gn_meta.get_acquisition_frameworks"))
        response = self.client.get(
            url_for("gn_meta.get_acquisition_frameworks"),
            query_string={
                'datasets': '1',
                'creator': '1',
                'actors': '1',
            },
        )
        assert response.status_code == 200

    def test_get_acquisition_frameworks_list(self, users):
        response = self.client.get(url_for("gn_meta.get_acquisition_frameworks_list"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['admin_user'])

        response = self.client.get(url_for("gn_meta.get_acquisition_frameworks_list"))
        assert response.status_code == 200

    def test_get_acquisition_framework(self, users, acquisition_frameworks):
        af_id = acquisition_frameworks['orphan_af'].id_acquisition_framework
        get_af_url = url_for("gn_meta.get_acquisition_framework", id_acquisition_framework=af_id)

        response = self.client.get(get_af_url)
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['self_user'])
        response = self.client.get(get_af_url)
        assert response.status_code == Forbidden.code

        set_logged_user_cookie(self.client, users['admin_user'])
        response = self.client.get(get_af_url)
        assert response.status_code == 200
    
    def test_datasets_permissions(self, app, datasets, users):
        ds = datasets['own_dataset']
        with app.test_request_context(headers=logged_user_headers(users['user'])):
            app.preprocess_request()
            assert ds.has_instance_permission(0) == False
            assert ds.has_instance_permission(1) == True
            assert ds.has_instance_permission(2) == True
            assert ds.has_instance_permission(3) == True

        with app.test_request_context(headers=logged_user_headers(users['associate_user'])):
            app.preprocess_request()
            assert ds.has_instance_permission(0) == False
            assert ds.has_instance_permission(1) == False
            assert ds.has_instance_permission(2) == True
            assert ds.has_instance_permission(3) == True

        with app.test_request_context(headers=logged_user_headers(users['stranger_user'])):
            app.preprocess_request()
            assert ds.has_instance_permission(0) == False
            assert ds.has_instance_permission(1) == False
            assert ds.has_instance_permission(2) == False
            assert ds.has_instance_permission(3) == True

        with app.test_request_context(headers=logged_user_headers(users['user'])):
            app.preprocess_request()
            ds_ids = [ ds.id_dataset for ds in datasets.values() ]
            qs = TDatasets.query.filter(
                    TDatasets.id_dataset.in_(ds_ids)
            )
            assert set(qs.filter_by_scope(0).all()) == set([])
            assert set(qs.filter_by_scope(1).all()) == set([
                datasets['own_dataset'],
            ])
            assert set(qs.filter_by_scope(2).all()) == set([
                datasets['own_dataset'],
                datasets['associate_dataset'],
            ])
            assert set(qs.filter_by_scope(3).all()) == set(datasets.values())

    def test_dataset_is_deletable(self, app, synthese_data, datasets):
        assert datasets['own_dataset'].is_deletable() == False  # there are synthese data attached to this DS
        assert datasets['orphan_dataset'].is_deletable() == True

    def test_delete_dataset(self, app, users, synthese_data, acquisition_frameworks, datasets):
        ds_id = datasets['own_dataset'].id_dataset

        response = self.client.delete(url_for("gn_meta.delete_dataset", ds_id=ds_id))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['noright_user'])

        # The user has no rights on METADATA module
        response = self.client.delete(url_for("gn_meta.delete_dataset", ds_id=ds_id))
        assert response.status_code == Forbidden.code
        assert 'METADATA' in response.json['description']

        set_logged_user_cookie(self.client, users['self_user'])

        # The user has right on METADATA module, but not on this specific DS
        response = self.client.delete(url_for("gn_meta.delete_dataset", ds_id=ds_id))
        assert response.status_code == Forbidden.code
        assert 'METADATA' not in response.json['description']

        set_logged_user_cookie(self.client, users['user'])

        # The DS can not be deleted due to attached rows in synthese
        response = self.client.delete(url_for("gn_meta.delete_dataset", ds_id=ds_id))
        assert response.status_code == Conflict.code

        set_logged_user_cookie(self.client, users['admin_user'])
        ds_id = datasets['orphan_dataset'].id_dataset

        response = self.client.delete(url_for("gn_meta.delete_dataset", ds_id=ds_id))
        assert response.status_code == 204

    def test_list_datasets(self, users):
        response = self.client.get(url_for("gn_meta.get_datasets"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['admin_user'])

        response = self.client.get(url_for("gn_meta.get_datasets"))
        assert response.status_code == 200

    def test_create_dataset(self, users):
        response = self.client.post(url_for("gn_meta.create_dataset"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['admin_user'])

        response = self.client.post(url_for("gn_meta.create_dataset"))
        assert response.status_code == BadRequest.code

    def test_get_dataset(self, users, datasets):
        ds = datasets['own_dataset']

        response = self.client.get(url_for("gn_meta.get_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['stranger_user'])
        response = self.client.get(url_for("gn_meta.get_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == Forbidden.code

        set_logged_user_cookie(self.client, users['associate_user'])
        response = self.client.get(url_for("gn_meta.get_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == 200
    
    def test_update_dataset(self, users, datasets):
        new_name = 'thenewname'
        ds = datasets['own_dataset']
        set_logged_user_cookie(self.client, users['user'])

        response = self.client.patch(url_for("gn_meta.update_dataset", 
                                             id_dataset=ds.id_dataset),
                                     data=dict(dataset_name=new_name))
        
        assert response.status_code == 200
        assert response.json.get('dataset_name') == new_name

    def test_update_dataset_not_found(self, users, datasets):
        ds = datasets['stranger_dataset']
        set_logged_user_cookie(self.client, users['user'])

        response = self.client.patch(url_for("gn_meta.update_dataset", 
                                            id_dataset=ds.id_dataset))

        assert response.status_code == NotFound.code

    def test_update_dataset_forbidden(self, users, datasets):
        ds = datasets['associate_dataset']
        set_logged_user_cookie(self.client, users['self_user'])

        response = self.client.patch(url_for("gn_meta.update_dataset", 
                                            id_dataset=ds.id_dataset))

        assert response.status_code == Forbidden.code

    def test_dataset_pdf_export(self, users, datasets):
        unexisting_id = db.session.query(func.max(TDatasets.id_dataset)).scalar() + 1
        ds = datasets['own_dataset']

        response = self.client.get(url_for("gn_meta.get_export_pdf_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['self_user'])

        response = self.client.get(url_for("gn_meta.get_export_pdf_dataset", id_dataset=unexisting_id))
        assert response.status_code == NotFound.code

        response = self.client.get(url_for("gn_meta.get_export_pdf_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == Forbidden.code

        set_logged_user_cookie(self.client, users['user'])

        response = self.client.get(url_for("gn_meta.get_export_pdf_dataset", id_dataset=ds.id_dataset))
        assert response.status_code == 200

    def test_uuid_report(self, users):
        response = self.client.get(url_for("gn_meta.uuid_report"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['user'])
        response = self.client.get(url_for("gn_meta.uuid_report"))
        assert response.status_code == 200

    def test_sensi_report(self, users):
        response = self.client.get(url_for("gn_meta.uuid_report"))
        assert response.status_code == Unauthorized.code

        set_logged_user_cookie(self.client, users['user'])
        response = self.client.get(url_for("gn_meta.uuid_report"))
        assert response.status_code == 200

    def test_get_af_from_id(self, af_list):
        id_af = 1

        found_af = get_af_from_id(id_af=id_af, af_list=af_list)

        assert isinstance(found_af, dict)
        assert found_af.get("id_acquisition_framework") == id_af

    def test_get_af_from_id_not_present(self, af_list):
        id_af = 12

        found_af = get_af_from_id(id_af=id_af, af_list=af_list)

        assert found_af is None

    def test_get_af_from_id_raise(self):
        id_af = 1
        af_list = [
            {"test": 2}
            ]
        
        found_ad = get_af_from_id(id_af=id_af, af_list=af_list)
