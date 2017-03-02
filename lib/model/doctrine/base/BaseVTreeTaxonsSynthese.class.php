<?php

/**
 * BaseVTreeTaxonsSynthese
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_nom
 * @property integer $cd_ref
 * @property string $nom_latin
 * @property string $nom_francais
 * @property integer $id_regne
 * @property string $nom_regne
 * @property integer $id_embranchement
 * @property string $nom_embranchement
 * @property integer $id_classe
 * @property string $nom_classe
 * @property string $desc_classe
 * @property integer $id_ordre
 * @property string $nom_ordre
 * @property integer $id_famille
 * @property string $nom_famille
 * @property BibNoms $BibNoms
 * 
 * @method integer             get()                  Returns the current record's "id_nom" value
 * @method integer             get()                  Returns the current record's "cd_ref" value
 * @method string              get()                  Returns the current record's "nom_latin" value
 * @method string              get()                  Returns the current record's "nom_francais" value
 * @method integer             get()                  Returns the current record's "id_regne" value
 * @method string              get()                  Returns the current record's "nom_regne" value
 * @method integer             get()                  Returns the current record's "id_embranchement" value
 * @method string              get()                  Returns the current record's "nom_embranchement" value
 * @method integer             get()                  Returns the current record's "id_classe" value
 * @method string              get()                  Returns the current record's "nom_classe" value
 * @method string              get()                  Returns the current record's "desc_classe" value
 * @method integer             get()                  Returns the current record's "id_ordre" value
 * @method string              get()                  Returns the current record's "nom_ordre" value
 * @method integer             get()                  Returns the current record's "id_famille" value
 * @method string              get()                  Returns the current record's "nom_famille" value
 * @method BibNoms             get()                  Returns the current record's "BibNoms" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_nom" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "cd_ref" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_latin" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_francais" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_regne" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_regne" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_embranchement" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_embranchement" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_classe" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_classe" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "desc_classe" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_ordre" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_ordre" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "id_famille" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "nom_famille" value
 * @method VTreeTaxonsSynthese set()                  Sets the current record's "BibNoms" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseVTreeTaxonsSynthese extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('synthese.v_tree_taxons_synthese');
        $this->hasColumn('id_nom', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('cd_ref', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('nom_latin', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('nom_francais', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('id_regne', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_regne', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('id_embranchement', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_embranchement', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('id_classe', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_classe', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('desc_classe', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('id_ordre', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_ordre', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('id_famille', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_famille', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('BibNoms', array(
             'local' => 'id_nom',
             'foreign' => 'id_nom'));
    }
}