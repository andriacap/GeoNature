<?php

/**
 * BaseTRelevesCf
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_releve_cf
 * @property integer $id_cf
 * @property integer $id_nom
 * @property integer $id_critere_cf
 * @property integer $am
 * @property integer $af
 * @property integer $ai
 * @property integer $na
 * @property integer $sai
 * @property integer $jeune
 * @property integer $yearling
 * @property integer $cd_ref_origine
 * @property string $nom_taxon_saisi
 * @property string $commentaire
 * @property string $determinateur
 * @property boolean $supprime
 * @property boolean $prelevement
 * @property BibCriteresCf $BibCriteresCf
 * @property BibNoms $BibNoms
 * @property TFichesCf $TFichesCf
 * @property Doctrine_Collection $VNomadeTaxonsFaune
 * 
 * @method integer             get()                   Returns the current record's "id_releve_cf" value
 * @method integer             get()                   Returns the current record's "id_cf" value
 * @method integer             get()                   Returns the current record's "id_nom" value
 * @method integer             get()                   Returns the current record's "id_critere_cf" value
 * @method integer             get()                   Returns the current record's "am" value
 * @method integer             get()                   Returns the current record's "af" value
 * @method integer             get()                   Returns the current record's "ai" value
 * @method integer             get()                   Returns the current record's "na" value
 * @method integer             get()                   Returns the current record's "sai" value
 * @method integer             get()                   Returns the current record's "jeune" value
 * @method integer             get()                   Returns the current record's "yearling" value
 * @method integer             get()                   Returns the current record's "cd_ref_origine" value
 * @method string              get()                   Returns the current record's "nom_taxon_saisi" value
 * @method string              get()                   Returns the current record's "commentaire" value
 * @method string              get()                   Returns the current record's "determinateur" value
 * @method boolean             get()                   Returns the current record's "supprime" value
 * @method boolean             get()                   Returns the current record's "prelevement" value
 * @method BibCriteresCf       get()                   Returns the current record's "BibCriteresCf" value
 * @method BibNoms             get()                   Returns the current record's "BibNoms" value
 * @method TFichesCf           get()                   Returns the current record's "TFichesCf" value
 * @method Doctrine_Collection get()                   Returns the current record's "VNomadeTaxonsFaune" collection
 * @method TRelevesCf          set()                   Sets the current record's "id_releve_cf" value
 * @method TRelevesCf          set()                   Sets the current record's "id_cf" value
 * @method TRelevesCf          set()                   Sets the current record's "id_nom" value
 * @method TRelevesCf          set()                   Sets the current record's "id_critere_cf" value
 * @method TRelevesCf          set()                   Sets the current record's "am" value
 * @method TRelevesCf          set()                   Sets the current record's "af" value
 * @method TRelevesCf          set()                   Sets the current record's "ai" value
 * @method TRelevesCf          set()                   Sets the current record's "na" value
 * @method TRelevesCf          set()                   Sets the current record's "sai" value
 * @method TRelevesCf          set()                   Sets the current record's "jeune" value
 * @method TRelevesCf          set()                   Sets the current record's "yearling" value
 * @method TRelevesCf          set()                   Sets the current record's "cd_ref_origine" value
 * @method TRelevesCf          set()                   Sets the current record's "nom_taxon_saisi" value
 * @method TRelevesCf          set()                   Sets the current record's "commentaire" value
 * @method TRelevesCf          set()                   Sets the current record's "determinateur" value
 * @method TRelevesCf          set()                   Sets the current record's "supprime" value
 * @method TRelevesCf          set()                   Sets the current record's "prelevement" value
 * @method TRelevesCf          set()                   Sets the current record's "BibCriteresCf" value
 * @method TRelevesCf          set()                   Sets the current record's "BibNoms" value
 * @method TRelevesCf          set()                   Sets the current record's "TFichesCf" value
 * @method TRelevesCf          set()                   Sets the current record's "VNomadeTaxonsFaune" collection
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTRelevesCf extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('contactfaune.t_releves_cf');
        $this->hasColumn('id_releve_cf', 'integer', 5, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 5,
             ));
        $this->hasColumn('id_cf', 'integer', 5, array(
             'type' => 'integer',
             'length' => 5,
             ));
        $this->hasColumn('id_nom', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('id_critere_cf', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('am', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('af', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('ai', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('na', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('sai', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('jeune', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('yearling', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('cd_ref_origine', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('nom_taxon_saisi', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('commentaire', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('determinateur', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('supprime', 'boolean', 1, array(
             'type' => 'boolean',
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('prelevement', 'boolean', 1, array(
             'type' => 'boolean',
             'notnull' => true,
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('BibCriteresCf', array(
             'local' => 'id_critere_cf',
             'foreign' => 'id_critere_cf'));

        $this->hasOne('BibNoms', array(
             'local' => 'id_nom',
             'foreign' => 'id_nom'));

        $this->hasOne('TFichesCf', array(
             'local' => 'id_cf',
             'foreign' => 'id_cf'));

        $this->hasMany('VNomadeTaxonsFaune', array(
             'local' => 'id_nom',
             'foreign' => 'id_nom'));
    }
}