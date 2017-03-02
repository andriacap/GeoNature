<?php

/**
 * BaseTaxrefProtectionEspeces
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $cd_nom
 * @property string $cd_protection
 * @property string $nom_cite
 * @property string $syn_cite
 * @property string $nom_francais_cite
 * @property string $precisions
 * @property string $cd_nom_cite
 * @property TaxrefProtectionArticles $TaxrefProtectionArticles
 * @property Taxref $Taxref
 * 
 * @method integer                  get()                         Returns the current record's "cd_nom" value
 * @method string                   get()                         Returns the current record's "cd_protection" value
 * @method string                   get()                         Returns the current record's "nom_cite" value
 * @method string                   get()                         Returns the current record's "syn_cite" value
 * @method string                   get()                         Returns the current record's "nom_francais_cite" value
 * @method string                   get()                         Returns the current record's "precisions" value
 * @method string                   get()                         Returns the current record's "cd_nom_cite" value
 * @method TaxrefProtectionArticles get()                         Returns the current record's "TaxrefProtectionArticles" value
 * @method Taxref                   get()                         Returns the current record's "Taxref" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "cd_nom" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "cd_protection" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "nom_cite" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "syn_cite" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "nom_francais_cite" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "precisions" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "cd_nom_cite" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "TaxrefProtectionArticles" value
 * @method TaxrefProtectionEspeces  set()                         Sets the current record's "Taxref" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTaxrefProtectionEspeces extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('taxonomie.taxref_protection_especes');
        $this->hasColumn('cd_nom', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('cd_protection', 'string', 20, array(
             'type' => 'string',
             'primary' => true,
             'length' => 20,
             ));
        $this->hasColumn('nom_cite', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('syn_cite', 'string', 200, array(
             'type' => 'string',
             'length' => 200,
             ));
        $this->hasColumn('nom_francais_cite', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('precisions', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('cd_nom_cite', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TaxrefProtectionArticles', array(
             'local' => 'cd_protection',
             'foreign' => 'cd_protection'));

        $this->hasOne('Taxref', array(
             'local' => 'cd_nom',
             'foreign' => 'cd_nom'));
    }
}