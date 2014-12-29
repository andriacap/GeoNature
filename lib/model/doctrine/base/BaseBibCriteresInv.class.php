<?php

/**
 * BaseBibCriteresInv
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_critere_inv
 * @property string $code_critere_inv
 * @property string $nom_critere_inv
 * @property integer $tri_inv
 * @property integer $id_critere_synthese
 * @property Doctrine_Collection $TRelevesInv
 * 
 * @method integer             getIdCritereInv()        Returns the current record's "id_critere_inv" value
 * @method string              getCodeCritereInv()      Returns the current record's "code_critere_inv" value
 * @method string              getNomCritereInv()       Returns the current record's "nom_critere_inv" value
 * @method integer             getTriInv()              Returns the current record's "tri_inv" value
 * @method integer             getIdCritereSynthese()   Returns the current record's "id_critere_synthese" value
 * @method Doctrine_Collection getTRelevesInv()         Returns the current record's "TRelevesInv" collection
 * @method BibCriteresInv      setIdCritereInv()        Sets the current record's "id_critere_inv" value
 * @method BibCriteresInv      setCodeCritereInv()      Sets the current record's "code_critere_inv" value
 * @method BibCriteresInv      setNomCritereInv()       Sets the current record's "nom_critere_inv" value
 * @method BibCriteresInv      setTriInv()              Sets the current record's "tri_inv" value
 * @method BibCriteresInv      setIdCritereSynthese()   Sets the current record's "id_critere_synthese" value
 * @method BibCriteresInv      setTRelevesInv()         Sets the current record's "TRelevesInv" collection
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBibCriteresInv extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('contactinv.bib_criteres_inv');
        $this->hasColumn('id_critere_inv', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('code_critere_inv', 'string', 3, array(
             'type' => 'string',
             'length' => 3,
             ));
        $this->hasColumn('nom_critere_inv', 'string', 90, array(
             'type' => 'string',
             'length' => 90,
             ));
        $this->hasColumn('tri_inv', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('id_critere_synthese', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('TRelevesInv', array(
             'local' => 'id_critere_inv',
             'foreign' => 'id_critere_inv'));
    }
}