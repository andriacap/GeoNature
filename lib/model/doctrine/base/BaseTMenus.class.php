<?php

/**
 * BaseTMenus
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_menu
 * @property string $nom_menu
 * @property string $desc_menu
 * @property integer $id_application
 * @property TApplications $TApplications
 * 
 * @method integer       get()               Returns the current record's "id_menu" value
 * @method string        get()               Returns the current record's "nom_menu" value
 * @method string        get()               Returns the current record's "desc_menu" value
 * @method integer       get()               Returns the current record's "id_application" value
 * @method TApplications get()               Returns the current record's "TApplications" value
 * @method TMenus        set()               Sets the current record's "id_menu" value
 * @method TMenus        set()               Sets the current record's "nom_menu" value
 * @method TMenus        set()               Sets the current record's "desc_menu" value
 * @method TMenus        set()               Sets the current record's "id_application" value
 * @method TMenus        set()               Sets the current record's "TApplications" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTMenus extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('utilisateurs.t_menus');
        $this->hasColumn('id_menu', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'sequence' => 't_menus_id_menu',
             'length' => 4,
             ));
        $this->hasColumn('nom_menu', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('desc_menu', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('id_application', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('TApplications', array(
             'local' => 'id_application',
             'foreign' => 'id_application'));
    }
}