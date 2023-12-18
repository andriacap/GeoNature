jsonschema_definitions = {
    "entity": {
        "type": "object",
        "properties": {
            "label": {
                "type": "string",
            },
        },
        "required": [
            "label",
        ],
        "additionalProperties": False,
    },
    "fields": {
        "type": "object",
        "properties": {
            "id_field": {
                "type": "integer",
            },
            "name_field": {
                "type": "string",
            },
            "fr_label": {
                "type": "string",
            },
            "eng_label": {
                "type": "string",
            },
            "desc_field": {
                "type": "string",
            },
            "type_field": {
                "type": "string",
            },
            "synthese_field": {
                "type": "boolean",
            },
            "mandatory": {
                "type": "boolean",
            },
            "autogenerated": {
                "type": "boolean",
            },
            "mnemonique": {
                "type": [
                    "string",
                    "null",
                ],
            },
            "id_theme": {
                "type": "integer",
            },
            "order_field": {
                "type": "integer",
            },
            "comment": {
                "type": [
                    "string",
                    "null",
                ],
            },
            "display": {
                "type": "boolean",
            },
            "multi": {
                "type": "boolean",
            },
        },
        "required": [
            "id_field",
            "name_field",
            "fr_label",
            "eng_label",
            "desc_field",
            "mandatory",
            "autogenerated",
            "comment",
        ],
        "additionalProperties": False,
    },
    "theme": {
        "type": "object",
        "properties": {
            "id_theme": {
                "type": "integer",
            },
            "name_theme": {
                "type": "string",
            },
            "fr_label_theme": {
                "type": "string",
            },
            "eng_label_theme": {
                "type": "string",
            },
            "desc_theme": {
                "type": "string",
            },
            "order_theme": {
                "type": "integer",
            },
        },
        "required": [
            "id_theme",
            "name_theme",
            "fr_label_theme",
            "eng_label_theme",
            "desc_theme",
        ],
        "additionalProperties": False,
    },
    "nomenclature_type": {
        "type": "object",
        "properties": {
            "id_type": {
                "type": "integer",
            },
            "mnemonique": {
                "type": "string",
            },
            "label_default": {
                "type": "string",
            },
            "definition_default": {
                "type": "string",
            },
        },
        "required": [
            "id_type",
            "mnemonique",
            "label_default",
            "definition_default",
        ],
    },
    "nomenclature": {
        "type": "object",
        "properties": {
            "cd_nomenclature": {
                "type": "string",
            },
            "mnemonique": {
                "type": "string",
            },
            "label_default": {
                "type": "string",
            },
            "definition_default": {
                "type": "string",
            },
            "source": {
                "type": "string",
            },
            "statut": {
                "type": "string",
            },
            "id_broader": {
                "type": "integer",
            },
            "hierarchy": {
                "type": "string",
            },
            "active": {
                "type": "boolean",
            },
            "meta_create_date": {
                "type": "string",
            },
            "meta_update_date": {
                "type": "string",
            },
        },
        "required": [
            "cd_nomenclature",
            "mnemonique",
            "label_default",
            "definition_default",
            "source",
            "statut",
            "id_broader",
            "hierarchy",
            "active",
        ],
    },
    "import": {
        "type": "object",
        "properties": {
            "id_import": {"type": "integer"},
            "format_source_file": {"type": ["string", "null"]},
            "srid": {"type": ["number", "null"]},
            "separator": {"type": ["string", "null"]},
            "encoding": {"type": ["string", "null"]},
            "detected_encoding": {"type": ["string", "null"]},
            # "import_table": {"type": ["string", "null"]},
            "full_file_name": {"type": ["string", "null"]},
            "id_dataset": {"type": ["integer", "null"]},
            "date_create_import": {"type": "string"},
            "date_update_import": {"type": "string"},
            "source_count": {"type": ["integer", "null"]},
            "import_count": {"type": ["integer", "null"]},
            "statistics": {"type": ["object", "null"]},
            "date_min_data": {"type": ["string", "null"]},
            "date_max_data": {"type": ["string", "null"]},
            "uuid_autogenerated": {"type": ["boolean", "null"]},
            "processed": {"type": ["boolean"]},
            "authors_name": {"type": "string"},
            "available_encodings": {"type": "array", "items": {"type": "string"}},
            "available_formats": {"type": "array", "items": {"type": "string"}},
            "available_separators": {"type": "array", "items": {"type": "string"}},
            # "columns": {
            #    "type": ["null", "array"],
            #    "items": {"type": "string"},
            # },
            "fieldmapping": {
                "type": ["null", "object"],
            },
            "contentmapping": {
                "type": ["null", "object"],
            },
            "errors_count": {
                "type": ["null", "integer"],
            },
        },
        "required": [
            "id_import",
            "format_source_file",
            "srid",
            "separator",
            "encoding",
            "detected_encoding",
            # "import_table",
            "full_file_name",
            "id_dataset",
            "date_create_import",
            "date_update_import",
            "source_count",
            "import_count",
            "statistics",
            "date_min_data",
            "date_max_data",
            "uuid_autogenerated",
            "processed",
            "errors_count",
            "authors_name",
            "available_encodings",
            "available_formats",
            "available_separators",
            "columns",
            "fieldmapping",
            "contentmapping",
        ],
    },
    "error": {
        "type": "object",
        "properties": {
            "pk": {"type": "integer"},
            "id_import": {"type": "integer"},
            "id_type": {"type": "integer"},
            "type": {"$ref": "#/definitions/error_type"},
            "column": {"type": "string"},
            "rows": {
                "type": "array",
                "items": {"type": "integer"},
            },
            "comment": {"type": ["null", "string"]},
        },
        "minProperties": 7,
        "additionalProperties": False,
    },
    "error_type": {
        "type": "object",
        "properties": {
            "pk": {"type": "integer"},
            "category": {"type": "string"},
            "name": {"type": "string"},
            "description": {"type": "string"},
            "level": {"type": "string"},
        },
        "minProperties": 5,
        "additionalProperties": False,
    },
    "mapping": {
        "type": "object",
        "properties": {
            "id": {"type": "integer"},
            "id_destination": {"type": "integer"},
            "label": {"type": "string"},
            "type": {
                "type": "string",
                "enum": ["FIELD", "CONTENT"],
            },
            "active": {"type": "boolean"},
            "public": {"type": "boolean"},
            "cruved": {"$ref": "#/definitions/cruved"},
            "values": {
                "type": ["null", "object"],
            },
        },
        "minProperties": 7,
        "additionalProperties": False,
    },
    "cruved": {
        "type": "object",
        "properties": {
            "C": {"type": "boolean"},
            "R": {"type": "boolean"},
            "U": {"type": "boolean"},
            "V": {"type": "boolean"},
            "E": {"type": "boolean"},
            "D": {"type": "boolean"},
        },
        "minProperties": 6,
        "additionalProperties": False,
    },
}
