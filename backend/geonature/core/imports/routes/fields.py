from itertools import groupby

from flask import jsonify
from sqlalchemy.orm import joinedload, contains_eager, selectinload

from geonature.core.gn_permissions import decorators as permissions
from pypnnomenclature.models import BibNomenclaturesTypes

from geonature.core.imports.models import (
    Entity,
    EntityField,
    BibFields,
    BibThemes,
)

from geonature.core.imports.blueprint import blueprint


@blueprint.route("/<destination>/fields", methods=["GET"])
@permissions.check_cruved_scope("C", get_scope=True, module_code="IMPORT", object_code="IMPORT")
def get_fields(scope, destination):
    """
    .. :quickref: Import; Get synthesis fields.

    Get all synthesis fields
    Use in field mapping steps
    You can find a jsonschema of the returned data in the associated test.
    """
    data = []
    entities = Entity.query.filter_by(destination=destination).order_by(Entity.order).all()
    for entity in entities:
        entity_fields = (
            EntityField.query.filter(EntityField.entity == entity)
            .filter(EntityField.field.has(BibFields.display == True))
            .join(BibThemes)
            .order_by(BibThemes.order_theme, EntityField.order_field)
            .options(selectinload(EntityField.field), selectinload(EntityField.theme))
            .all()
        )
        themes = []
        for id_theme, efs in groupby(entity_fields, lambda ef: ef.theme.id_theme):
            efs = list(efs)
            themes.append(
                {
                    "theme": efs[0].theme.as_dict(
                        fields=[
                            "id_theme",
                            "name_theme",
                            "fr_label_theme",
                            "eng_label_theme",
                            "desc_theme",
                        ],
                    ),
                    # Front retro-compat: we flatten entityfield and field
                    "fields": [
                        ef.as_dict(
                            fields=[
                                "desc_field",
                                "comment",
                            ],
                        )
                        | ef.field.as_dict(
                            fields=[
                                "id_field",
                                "name_field",
                                "fr_label",
                                "eng_label",
                                "mandatory",
                                "autogenerated",
                                "multi",
                            ]
                        )
                        for ef in efs
                    ],
                }
            )
        data.append(
            {
                "entity": entity.as_dict(fields=["label"]),
                "themes": themes,
            }
        )
    return jsonify(data)


@blueprint.route("/<destination>/nomenclatures", methods=["GET"])
def get_nomenclatures(destination):
    nomenclature_fields = (
        BibFields.query.filter(BibFields.destination == destination)
        .filter(BibFields.nomenclature_type != None)
        .options(
            joinedload(BibFields.nomenclature_type).joinedload(
                BibNomenclaturesTypes.nomenclatures
            ),
        )
        .all()
    )
    return jsonify(
        {
            field.nomenclature_type.mnemonique: {
                "nomenclature_type": field.nomenclature_type.as_dict(),
                "nomenclatures": {
                    nomenclature.cd_nomenclature: nomenclature.as_dict()
                    for nomenclature in field.nomenclature_type.nomenclatures
                },
            }
            for field in nomenclature_fields
        }
    )
