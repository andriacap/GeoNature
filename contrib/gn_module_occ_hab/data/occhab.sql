CREATE SCHEMA pr_occhab;

CREATE TABLE pr_occhab.t_stations(
  id_station serial NOT NULL,
  unique_id_sinp_station uuid NOT NULL DEFAULT public.uuid_generate_v4(),
  id_dataset integer NOT NULL,
  date_min timestamp without time zone DEFAULT now() NOT NULL,
  date_max timestamp without time zone DEFAULT now() NOT NULL,
  observers_txt varchar(500),
  station_name varchar(1000),
  is_habitat_complex boolean,
  id_nomenclature_exposure integer,
  altitude_min integer,
  altitude_max integer,
  depth_min integer,
  depth_max integer,
  area integer,
  id_nomenclature_area_surface_calculation integer,
  comment text,
  geom_4326 geometry NOT NULL
);

COMMENT ON COLUMN t_stations.id_nomenclature_exposure IS 'Correspondance nomenclature INPN = exposition d''un terrain, REF_NOMENCLATURES = EXPOSITION';

COMMENT ON COLUMN t_stations.id_nomenclature_area_surface_calculation IS 'Correspondance nomenclature INPN = exposition d''un terrain, REF_NOMENCLATURES = EXPOSITION';


CREATE TABLE pr_occhab.t_habitats(
  id_habitat serial NOT NULL,
  id_station integer NOT NULL,
  unique_id_sinp_hab uuid NOT NULL DEFAULT public.uuid_generate_v4(),
  cd_hab integer NOT NULL,
  nom_cite character varying(500) NOT NULL,
  id_nomenclature_determination_type integer,
  determiner character varying(500),
  id_nomenclature_collection_technique integer NOT NULL,
  recovery_percentage decimal,
  id_nomenclature_abundance integer,
  technical_precision character varying(500),
  unique_id_sinp_grp_occtax uuid,
  unique_id_sinp_grp_phyto uuid,
  id_nomenclature_sensitvity integer,
  id_nomenclature_geographic_object integer
);

CREATE TABLE pr_occhab.cor_station_observer(
  id_cor_station_observer integer NOT NULL,
  id_station integer NOT NULL,
  id_role integer NOT NULL
);

CREATE TABLE pr_occhab.defaults_nomenclatures_value (
    mnemonique_type character varying(255) NOT NULL,
    id_organism integer NOT NULL DEFAULT 0,
    regne character varying(20) NOT NULL DEFAULT '0',
    group2_inpn character varying(255) NOT NULL DEFAULT '0',
    id_nomenclature integer NOT NULL
);


----------------
--PRIMARY KEYS--
----------------

ALTER TABLE ONLY pr_occhab.t_stations
    ADD CONSTRAINT pk_t_stations PRIMARY KEY (id_station);

ALTER TABLE ONLY pr_occhab.t_habitats
    ADD CONSTRAINT pk_t_stations PRIMARY KEY (id_habitat);

ALTER TABLE ONLY pr_occhab.cor_station_observer
    ADD CONSTRAINT pk_t_stations PRIMARY KEY (id_cor_station_observer);

ALTER TABLE ONLY pr_occhab.defaults_nomenclatures_value
    ADD CONSTRAINT pk_pr_occhab_defaults_nomenclatures_value PRIMARY KEY (mnemonique_type, id_organism, regne, group2_inpn);


----------------
--FOREIGN KEYS--
----------------
   
ALTER TABLE ONLY pr_occhab.t_stations
ADD CONSTRAINT fk_t_releves_occtax_t_datasets FOREIGN KEY (id_dataset) REFERENCES gn_meta.t_datasets(id_dataset) ON UPDATE CASCADE;


ALTER TABLE ONLY pr_occhab.t_stations
ADD CONSTRAINT fk_t_stations_id_nomenclature_exposure FOREIGN KEY (id_nomenclature_exposure) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_stations
ADD CONSTRAINT fk_t_stations_id_nomenclature_area_surface_calculation FOREIGN KEY (id_nomenclature_area_surface_calculation) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;


ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_station FOREIGN KEY (id_station) REFERENCES pr_occhab.t_stations(id_station) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_nomenclature_determination_type FOREIGN KEY (id_nomenclature_determination_type) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_nomenclature_collection_technique FOREIGN KEY (id_nomenclature_collection_technique) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_nomenclature_abundance FOREIGN KEY (id_nomenclature_abundance) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_nomenclature_sensitvity FOREIGN KEY (id_nomenclature_sensitvity) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.t_habitats
ADD CONSTRAINT fk_t_habitats_id_nomenclature_community_interest FOREIGN KEY (id_nomenclature_community_interest) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;


ALTER TABLE ONLY pr_occhab.cor_station_observer
ADD CONSTRAINT fk_cor_station_observer_t_role FOREIGN KEY (id_role) REFERENCES utilisateurs.t_roles(id_role) ON UPDATE CASCADE;

ALTER TABLE ONLY pr_occhab.cor_station_observer
ADD CONSTRAINT fk_cor_station_observer_id_station FOREIGN KEY (id_station) REFERENCES pr_occhab.t_stations(id_station) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY defaults_nomenclatures_value
    ADD CONSTRAINT fk_pr_occhab_defaults_nomenclatures_value_mnemonique_type FOREIGN KEY (mnemonique_type) REFERENCES ref_nomenclatures.bib_nomenclatures_types(mnemonique) ON UPDATE CASCADE;

ALTER TABLE ONLY defaults_nomenclatures_value
    ADD CONSTRAINT fk_pr_occhab_defaults_nomenclatures_value_id_organism FOREIGN KEY (id_organism) REFERENCES utilisateurs.bib_organismes(id_organisme) ON UPDATE CASCADE;

ALTER TABLE ONLY defaults_nomenclatures_value
    ADD CONSTRAINT fk_pr_occhab_defaults_nomenclatures_value_id_nomenclature FOREIGN KEY (id_nomenclature) REFERENCES ref_nomenclatures.t_nomenclatures(id_nomenclature) ON UPDATE CASCADE;



---------------
--CONSTRAINTS--
---------------

ALTER TABLE ONLY pr_occhab.cor_station_observer
    ADD CONSTRAINT unique_cor_station_observer UNIQUE (id_station, id_role);

ALTER TABLE ONLY pr_occhab.t_stations
    ADD CONSTRAINT t_stations_altitude_max CHECK (altitude_max >= altitude_min);

ALTER TABLE ONLY pr_occhab.t_stations
    ADD CONSTRAINT t_stations_date_max CHECK (date_min >= date_max);

ALTER TABLE pr_occhab.t_stations
  ADD CONSTRAINT check_t_stations_exposure CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_exposure,'EXPOSURE')) NOT VALID;

ALTER TABLE pr_occhab.t_stations
  ADD CONSTRAINT check_t_stations_area_method CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_area_surface_calculation,'METHOD_CALCUL_SURFACE')) NOT VALID;

ALTER TABLE pr_occhab.t_habitats
ADD CONSTRAINT check_t_habitats_determini_meth CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_determination_type,'DETERMINATION_TYP_HAB')) NOT VALID;

ALTER TABLE pr_occhab.t_habitats
  ADD CONSTRAINT check_t_habitats_collection_techn CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_collection_technique,'TECHNIQUE_COLLECT_HAB')) NOT VALID;

ALTER TABLE pr_occhab.t_habitats
  ADD CONSTRAINT check_t_habitats_abondance CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_abundance,'ABONDANCE_HAB')) NOT VALID;

ALTER TABLE pr_occhab.t_habitats
  ADD CONSTRAINT check_t_habitats_sensitivity CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_sensitvity,'SENSIBILITE')) NOT VALID;

ALTER TABLE pr_occhab.t_habitats
  ADD CONSTRAINT check_t_habitats_community_interest CHECK (ref_nomenclatures.check_nomenclature_type_by_mnemonique(id_nomenclature_community_interest,'HAB_INTERET_COM')) NOT VALID;

