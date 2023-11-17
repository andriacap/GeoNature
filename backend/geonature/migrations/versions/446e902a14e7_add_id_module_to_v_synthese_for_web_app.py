"""add id_module to v_synthese_for_web_app

Revision ID: 446e902a14e7
Revises: f1dd984bff97
Create Date: 2023-09-25 10:09:39.126531

"""
import importlib

from alembic import op
from sqlalchemy.sql import text

# revision identifiers, used by Alembic.
revision = "446e902a14e7"
down_revision = "f1dd984bff97"
branch_labels = None
depends_on = None


def upgrade():
    conn = op.get_bind()
    path = "geonature.migrations.data.core.gn_synthese"
    filename = "v_synthese_for_web_app_add_id_module_v1.0.1.sql"
    conn.execute(text(importlib.resources.read_text(path, filename)))


def downgrade():
    conn = op.get_bind()
    path = "geonature.migrations.data.core.gn_synthese"
    filename = "initial_v_synthese_for_web_app_v1.0.0.sql"
    conn.execute(text(importlib.resources.read_text(path, filename)))
