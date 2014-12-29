<?php

/**
 * BaseBibOrganismes
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_organisme
 * @property string $nom_organisme
 * @property string $adresse_organisme
 * @property string $cp_organisme
 * @property string $tel_organisme
 * @property string $fax_organisme
 * @property string $email_organisme
 * @property Doctrine_Collection $Synthesefaune
 * @property Doctrine_Collection $TRoles
 * 
 * @method integer             getIdOrganisme()       Returns the current record's "id_organisme" value
 * @method string              getNomOrganisme()      Returns the current record's "nom_organisme" value
 * @method string              getAdresseOrganisme()  Returns the current record's "adresse_organisme" value
 * @method string              getCpOrganisme()       Returns the current record's "cp_organisme" value
 * @method string              getTelOrganisme()      Returns the current record's "tel_organisme" value
 * @method string              getFaxOrganisme()      Returns the current record's "fax_organisme" value
 * @method string              getEmailOrganisme()    Returns the current record's "email_organisme" value
 * @method Doctrine_Collection getSynthesefaune()     Returns the current record's "Synthesefaune" collection
 * @method Doctrine_Collection getTRoles()            Returns the current record's "TRoles" collection
 * @method BibOrganismes       setIdOrganisme()       Sets the current record's "id_organisme" value
 * @method BibOrganismes       setNomOrganisme()      Sets the current record's "nom_organisme" value
 * @method BibOrganismes       setAdresseOrganisme()  Sets the current record's "adresse_organisme" value
 * @method BibOrganismes       setCpOrganisme()       Sets the current record's "cp_organisme" value
 * @method BibOrganismes       setTelOrganisme()      Sets the current record's "tel_organisme" value
 * @method BibOrganismes       setFaxOrganisme()      Sets the current record's "fax_organisme" value
 * @method BibOrganismes       setEmailOrganisme()    Sets the current record's "email_organisme" value
 * @method BibOrganismes       setSynthesefaune()     Sets the current record's "Synthesefaune" collection
 * @method BibOrganismes       setTRoles()            Sets the current record's "TRoles" collection
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBibOrganismes extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('utilisateurs.bib_organismes');
        $this->hasColumn('id_organisme', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'sequence' => 'bib_organismes_id',
             'length' => 4,
             ));
        $this->hasColumn('nom_organisme', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('adresse_organisme', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('cp_organisme', 'string', 5, array(
             'type' => 'string',
             'length' => 5,
             ));
        $this->hasColumn('tel_organisme', 'string', 14, array(
             'type' => 'string',
             'length' => 14,
             ));
        $this->hasColumn('fax_organisme', 'string', 14, array(
             'type' => 'string',
             'length' => 14,
             ));
        $this->hasColumn('email_organisme', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Synthesefaune', array(
             'local' => 'id_organisme',
             'foreign' => 'id_organisme'));

        $this->hasMany('TRoles', array(
             'local' => 'id_organisme',
             'foreign' => 'id_organisme'));
    }
}