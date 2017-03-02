<?php

/**
 * BaseCorZonesstatutSynthese
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_zone
 * @property integer $id_synthese
 * @property Syntheseff $Syntheseff
 * @property LZonesstatut $LZonesstatut
 * 
 * @method integer                get()             Returns the current record's "id_zone" value
 * @method integer                get()             Returns the current record's "id_synthese" value
 * @method Syntheseff             get()             Returns the current record's "Syntheseff" value
 * @method LZonesstatut           get()             Returns the current record's "LZonesstatut" value
 * @method CorZonesstatutSynthese set()             Sets the current record's "id_zone" value
 * @method CorZonesstatutSynthese set()             Sets the current record's "id_synthese" value
 * @method CorZonesstatutSynthese set()             Sets the current record's "Syntheseff" value
 * @method CorZonesstatutSynthese set()             Sets the current record's "LZonesstatut" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCorZonesstatutSynthese extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('synthese.cor_zonesstatut_synthese');
        $this->hasColumn('id_zone', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('id_synthese', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Syntheseff', array(
             'local' => 'id_synthese',
             'foreign' => 'id_synthese'));

        $this->hasOne('LZonesstatut', array(
             'local' => 'id_zone',
             'foreign' => 'id_zone'));
    }
}