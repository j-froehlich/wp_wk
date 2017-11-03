<?php
/**
 * WilokeRedisCaching Class
 *
 * @category RedisCaching
 * @package Wiloke Framework
 * @author Wiloke Team
 * @version 1.0
 */

class WilokeRedisTermCaching{

    public function __construct()
    {
        add_action('create_term', array($this, 'add_new_term', 10, 3));
        add_action('delete_term', array($this, 'delete_term', 10, 3));
    }

    /**
     * @since 0.5
     * @see wp-includes/taxonomy.php line 2432
     * Anytime you hear a sound of add new term, please update caching for me
     */
    public function add_new_term($term_id, $tt_id, $taxonomy){
        global $wiloke;

        if ( in_array($taxonomy, $wiloke->aConfigs['caching']['taxonomy']) ) {
            self::getTerm($taxonomy, $term_id);
        }
    }

    /**
     * Clear term caching
     * @since 0.5
     */
    public function delete_term($term, $tt_id, $taxonomy){
        self::deleteTerms($term->term_id, $taxonomy);
    }

    public function getTerm($taxonomy, $termID){
        $aTerms = Wiloke::getRedisCache($taxonomy);

        if ( isset($aTerms[$termID]) ) {
            return array($termID=>(object)$aTerms[$termID]);
        }

        $oTerm = get_terms(
            array(
                'taxonomy' => $taxonomy,
                'include'  => array($termID)
            )
        );

        if ( empty($oTerm) || is_wp_error($oTerm) ) {
            return false;
        }

        self::updateTerms($taxonomy, array($termID=>$oTerm));

        return $oTerm;
    }

    public static function getTerms($taxonomy, $termIDs=null){
        $oTerms = Wiloke::getRedisCache($taxonomy);

        if ( $oTerms ) {
            if ( is_string($termIDs) ) {
                return $oTerms[$termIDs];
            }else{
                $aTarget = array();
                foreach ( $oTerms as $termID => $oTerm ) {
                    $aTarget[$termID] = (object)$oTerm;
                }

                return $aTarget;
            }
        }

        if ( !empty($termIDs) ) {
            $termIDs = is_string($termIDs) ? explode(',', $termIDs) : $termIDs;
            $oResults = array();

            foreach ( $termIDs as $termID ) {
                $oResults[$termID] = Wiloke::getTermCaching($taxonomy, $termID);
            }

            return $oResults;
        }

        $oTerms = get_terms(
            array(
                'taxonomy' => $taxonomy,
                'include'  => $termIDs
            )
        );

        if ( empty($oTerms) || is_wp_error($oTerms) ) {
            return false;
        }

        self::setTerms($taxonomy, $oTerms);

        return $oTerms;
    }

    public static function setTerms($taxonomy, $aData){
        if ( empty($aData) ) {
            return false;
        }

        if ( !Wiloke::setRedisCache($taxonomy, $aData) ) {
            Wiloke::setTermsCaching($taxonomy, $aData);
        }

        return $aData;
    }

    public static function updateTerms($taxonomy, $aData){
        $aTerms = Wiloke::getRedisCache($taxonomy);

        if ( !$aTerms ) {
            return;
        }

        foreach ( $aData as $termID => $oTerm ){
            if ( isset($aTerms[$termID]) ) {
                continue;
            }

            $aTerms[$termID] = get_object_vars($aData);
        }

        self::setTerms($taxonomy, $aTerms);
    }

    public static function deleteTerms($termIDs, $taxonomy){
        $aTerms = Wiloke::getRedisCache($taxonomy);

        if ( empty($aTerms) || !$aTerms ) {
            return;
        }

        if ( is_array($termIDs) ) {
            foreach ( $termIDs as $termID ){
                unset($aTerms[$termID]);
            }
        }else{
            unset($aTerms[$termIDs]);
        }

        if ( !empty($aTerms) ) {
            self::setTerms($taxonomy, $aTerms);
        }else{
            self::$wilokePredis->del($taxonomy);
        }
    }
}