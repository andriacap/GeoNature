"""
    Définition de routes "génériques"
    c-a-d pouvant servir à tous module
"""

import os
import logging

from flask import Blueprint, request, current_app, jsonify

from geonature.utils.env import DB
from geonature.core.gn_monitoring.config_manager import generate_config


# from pypnusershub import routes as fnauth


routes = Blueprint("core", __name__)

# get the root logger
log = logging.getLogger()


@routes.route("/config", methods=["GET"])
def get_config():
    """
    Parse and return configuration files as toml
    .. :quickref: Generic;
    """
    app_name = request.args.get("app", "base_app")
    vue_name = request.args.getlist("vue")

    conf_path = os.path.abspath(
            os.path.join(current_app.config["BASE_DIR"], "static", "configs", app_name, *vue_name)
    )
    # Add test : file inside config folder
    if not conf_path.startswith(os.path.abspath(os.path.join(current_app.static_folder, "config"))):
        return "Not a valid config path", 404

    if not vue_name:
        vue_name = ["default"]
    filename = "{}.toml".format(
        conf_path
    )
    config_file = generate_config(filename)
    return jsonify(config_file)
