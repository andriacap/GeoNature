<?php

/**
 * BaseCorMessageTaxonCf
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_message_cf
 * @property integer $id_taxon
 * @property BibTaxons $BibTaxons
 * @property BibMessagesCf $BibMessagesCf
 * 
 * @method integer           getIdMessageCf()   Returns the current record's "id_message_cf" value
 * @method integer           getIdTaxon()       Returns the current record's "id_taxon" value
 * @method BibTaxons         getBibTaxons()     Returns the current record's "BibTaxons" value
 * @method BibMessagesCf     getBibMessagesCf() Returns the current record's "BibMessagesCf" value
 * @method CorMessageTaxonCf setIdMessageCf()   Sets the current record's "id_message_cf" value
 * @method CorMessageTaxonCf setIdTaxon()       Sets the current record's "id_taxon" value
 * @method CorMessageTaxonCf setBibTaxons()     Sets the current record's "BibTaxons" value
 * @method CorMessageTaxonCf setBibMessagesCf() Sets the current record's "BibMessagesCf" value
 * 
 * @package    geonature
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCorMessageTaxonCf extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('contactfaune.cor_message_taxon');
        $this->hasColumn('id_message_cf', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('id_taxon', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('BibTaxons', array(
             'local' => 'id_taxon',
             'foreign' => 'id_taxon'));

        $this->hasOne('BibMessagesCf', array(
             'local' => 'id_message_cf',
             'foreign' => 'id_message_cf'));
    }
}