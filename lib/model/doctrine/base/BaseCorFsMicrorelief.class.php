<?php

/**
 * BaseCorFsMicrorelief
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_station
 * @property integer $id_microrelief
 * @property TStationsFs $TStationsFs
 * @property BibMicroreliefs $BibMicroreliefs
 * 
 * @method integer          get()                Returns the current record's "id_station" value
 * @method integer          get()                Returns the current record's "id_microrelief" value
 * @method TStationsFs      get()                Returns the current record's "TStationsFs" value
 * @method BibMicroreliefs  get()                Returns the current record's "BibMicroreliefs" value
 * @method CorFsMicrorelief set()                Sets the current record's "id_station" value
 * @method CorFsMicrorelief set()                Sets the current record's "id_microrelief" value
 * @method CorFsMicrorelief set()                Sets the current record's "TStationsFs" value
 * @method CorFsMicrorelief set()                Sets the current record's "BibMicroreliefs" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCorFsMicrorelief extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('florestation.cor_fs_microrelief');
        $this->hasColumn('id_station', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('id_microrelief', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TStationsFs', array(
             'local' => 'id_station',
             'foreign' => 'id_station'));

        $this->hasOne('BibMicroreliefs', array(
             'local' => 'id_microrelief',
             'foreign' => 'id_microrelief'));
    }
}