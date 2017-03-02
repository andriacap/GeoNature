<?php

/**
 * BaseBibThemes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_theme
 * @property string $nom_theme
 * @property string $desc_theme
 * @property integer $ordre
 * @property Doctrine_Collection $BibAttributs
 * 
 * @method integer             get()             Returns the current record's "id_theme" value
 * @method string              get()             Returns the current record's "nom_theme" value
 * @method string              get()             Returns the current record's "desc_theme" value
 * @method integer             get()             Returns the current record's "ordre" value
 * @method Doctrine_Collection get()             Returns the current record's "BibAttributs" collection
 * @method BibThemes           set()             Sets the current record's "id_theme" value
 * @method BibThemes           set()             Sets the current record's "nom_theme" value
 * @method BibThemes           set()             Sets the current record's "desc_theme" value
 * @method BibThemes           set()             Sets the current record's "ordre" value
 * @method BibThemes           set()             Sets the current record's "BibAttributs" collection
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBibThemes extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('taxonomie.bib_taxref_statuts');
        $this->hasColumn('id_theme', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('nom_theme', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('desc_theme', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('ordre', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('BibAttributs', array(
             'local' => 'id_theme',
             'foreign' => 'id_theme'));
    }
}