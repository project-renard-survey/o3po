<?php

/**
 * Class representing the secondary publication type.
 *
 * Each publication type is connected to a WordPress custom post type and
 * individual publications are represented by posts of that type.
 *
 * @link       http://example.com
 * @since      0.1.0
 *
 * @package    O3PO
 * @subpackage O3PO/includes
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-o3po-publication-type.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-o3po-latex.php';

/**
 * Class representing the secondary publication type.
 *
 * @since      0.1.0
 * @package    O3PO
 * @subpackage O3PO/includes
 * @author     Christian Gogolin <o3po@quantum-journal.org>
 */
class O3PO_SecondaryPublicationType extends O3PO_PublicationType {

        /**
         * Name of the publication type on which this publication type
         * can be meta literature.
         *
         * @since    0.1.0
         * @access   private
         */
    private $targe_publication_type_name;

        /**
         * Plural name of the publication type on which this publication
         * type can be meta literature.
         *
         * @since    0.1.0
         * @access   private
         */
    private $targe_publication_type_name_plural;


       /**
         * Construct this publication type.
         *
         * Constructs and registers this publication type in the array
         * static::$active_publication_types. Throws an error in case a
         * publication type with the same $publication_type_name is alreay
         * registered.
         *
         * @since    0.1.0
         * @access   public
         * @param    string               $targe_publication_type_name           Name of the publication type targeted by publications of this type.
         * @param    string               $targe_publication_type_name_plural    Plural of the name of the publication type targeted by publications of this type.
         * @param    O3PO_Journal         $journal                               The journal this publication type is associated with.
         * @param    O3PO_Environment     $environment                           The evironment in which this post type is to be created.
         */
    public function __construct( $targe_publication_type_name, $targe_publication_type_name_plural, $journal, $environment ) {

        parent::__construct($journal, 1, $environment);

        $this->targe_publication_type_name = $targe_publication_type_name;
        $this->targe_publication_type_name_plural = $targe_publication_type_name_plural;
    }

        /**
         * Get the categories associated with this publication type
         *
         * The publication type View comes in different flavors, depending
         * on the content and authors who wrote it.
         *
         * @since    0.1.0
         * @access   public
         */
    public static function get_associated_categories() {

        return array("Perspective", "Editorial", "Leap");
    }


        /**
         * Render the admin panel meta box.
         *
         * @since     0.1.0
         * @access    public
         * @param     Post    $post    Post for which the meta box is to be rendered.
         */
    public function render_metabox( $post ) {

        $post_id = $post->ID;
        $post_type = get_post_type($post_id);
            // If the post type doesn't fit do nothing
        if ( $this->get_publication_type_name() !== $post_type )
            return;

        parent::render_metabox( $post );

        $post_id = $post->ID;

        $this->the_admin_panel_intro_text($post_id);
        $this->the_admin_panel_validation_result($post_id);
        echo '<table class="form-table">';
        $this->the_admin_panel_sub_type($post_id);
        $this->the_admin_panel_target_dois($post_id);
        $this->the_admin_panel_title($post_id);
        $this->the_admin_panel_corresponding_author_email($post_id);
        $this->the_admin_panel_buffer_email($post_id);
        $this->the_admin_panel_authors($post_id);
        $this->the_admin_panel_affiliations($post_id);
        $this->the_admin_panel_date_volume_pages($post_id);
        $this->the_admin_panel_doi($post_id);
        $post_type = get_post_type($post_id);
        $sub_type = get_post_meta( $post_id, $post_type . '_type', true );
        if($sub_type==="Leap")
        {
            static::the_admin_panel_reviewers_summary($post_id);
            static::the_admin_panel_reviewers($post_id);
            static::the_admin_panel_reviewer_institutions($post_id);
            static::the_admin_panel_author_commentary($post_id);
        }
        $this->the_admin_panel_bibliography($post_id);
        $this->the_admin_panel_crossref($post_id);
        $this->the_admin_panel_doaj($post_id);
        $this->the_admin_panel_clockss($post_id);
        echo '</table>';
    }

        /**
         * Callback function for handling the data enterd into the meta-box
         * when a correspnding post is saved.
         *
         * Warning: This is already called when a New Post is created and not
         * only when the "Publish" or "Update" button is pressed!
         *
         * @since     0.1.0
         * @access    public
         * @param     int     $post_id     Id of the post whose meta data is to be saved.
         * */
    protected function save_meta_data( $post_id ) {

        parent::save_meta_data($post_id);

        $post_type = get_post_type($post_id);

        $new_type = isset( $_POST[ $post_type . '_type' ] ) ? sanitize_text_field( $_POST[ $post_type . '_type' ] ) : '';
        $new_number_target_dois = isset( $_POST[ $post_type . '_number_target_dois' ] ) ? sanitize_text_field( $_POST[ $post_type . '_number_target_dois' ] ) : '';
        $new_target_dois = array();
		for ($x = 0; $x < $new_number_target_dois; $x++) {
			$new_target_dois[] = isset( $_POST[ $post_type . '_target_dois' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_target_dois' ][$x] ) : '';
		}

        $new_reviewers_summary = isset( $_POST[ $post_type . '_reviewers_summary' ] ) ? $_POST[ $post_type . '_reviewers_summary' ] : '';

		$new_number_reviewers = isset( $_POST[ $post_type . '_number_reviewers' ] ) ? sanitize_text_field( $_POST[ $post_type . '_number_reviewers' ] ) : '';
		for ($x = 0; $x < $new_number_reviewers; $x++) {
			$new_reviewer_given_names[] = isset( $_POST[ $post_type . '_reviewer_given_names' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_given_names' ][$x] ) : '';
			$new_reviewer_surnames[] = isset( $_POST[ $post_type . '_reviewer_surnames' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_surnames' ][$x] ) : '';
			$new_reviewer_name_styles[] = isset( $_POST[ $post_type . '_reviewer_name_styles' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_name_styles' ][$x] ) : '';
			$affiliation_nums = isset( $_POST[ $post_type . '_reviewer_affiliations' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_affiliations' ][$x] ) : '';
			$affiliation_nums = trim( preg_replace("/[^,0-9]/", "", $affiliation_nums ), ',');
			$new_reviewer_affiliations[] = $affiliation_nums;
			$new_reviewer_orcids[] = isset( $_POST[ $post_type . '_reviewer_orcids' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_orcids' ][$x] ) : '';
            $new_reviewer_urls[] = isset( $_POST[ $post_type . '_reviewer_urls' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_urls' ][$x] ) : '';

            $new_reviewer_ages[] = isset( $_POST[ $post_type . '_reviewer_ages' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_ages' ][$x] ) : '';
            $new_reviewer_grades[] = isset( $_POST[ $post_type . '_reviewer_grades' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_grades' ][$x] ) : '';
		}
        $new_number_reviewer_institutions = isset( $_POST[ $post_type . '_number_reviewer_institutions' ] ) ? sanitize_text_field( $_POST[ $post_type . '_number_reviewer_institutions' ] ) : '';
        $new_reviewer_institutions = array();
		for ($x = 0; $x < $new_number_reviewer_institutions; $x++) {
			$new_reviewer_institutions[] = isset( $_POST[ $post_type . '_reviewer_institutions' ][$x] ) ? sanitize_text_field( $_POST[ $post_type . '_reviewer_institutions' ][$x] ) : '';
		}
        $new_author_commentary = isset( $_POST[ $post_type . '_author_commentary' ] ) ? $_POST[ $post_type . '_author_commentary' ] : '';
        $new_about_the_author = isset( $_POST[ $post_type . '_about_the_author' ] ) ? $_POST[ $post_type . '_about_the_author' ] : '';

        update_post_meta( $post_id, $post_type . '_type', $new_type );
        update_post_meta( $post_id, $post_type . '_number_target_dois', $new_number_target_dois );
        update_post_meta( $post_id, $post_type . '_target_dois', $new_target_dois );
        update_post_meta( $post_id, $post_type . '_reviewers_summary', $new_reviewers_summary );

        update_post_meta( $post_id, $post_type . '_number_reviewers', $new_number_reviewers );
		update_post_meta( $post_id, $post_type . '_reviewer_given_names', $new_reviewer_given_names );
		update_post_meta( $post_id, $post_type . '_reviewer_surnames', $new_reviewer_surnames );
		update_post_meta( $post_id, $post_type . '_reviewer_name_styles', $new_reviewer_name_styles );
		update_post_meta( $post_id, $post_type . '_reviewer_affiliations', $new_reviewer_affiliations );
		update_post_meta( $post_id, $post_type . '_reviewer_orcids', $new_reviewer_orcids );
        update_post_meta( $post_id, $post_type . '_reviewer_urls', $new_reviewer_urls );
        update_post_meta( $post_id, $post_type . '_reviewer_ages', $new_reviewer_ages );
        update_post_meta( $post_id, $post_type . '_reviewer_grades', $new_reviewer_grades );
        update_post_meta( $post_id, $post_type . '_number_reviewer_institutions', $new_number_reviewer_institutions );
		update_post_meta( $post_id, $post_type . '_reviewer_institutions', $new_reviewer_institutions );

        update_post_meta( $post_id, $post_type . '_author_commentary', $new_author_commentary );
        update_post_meta( $post_id, $post_type . '_about_the_author', $new_about_the_author );
    }

        /**
         * Validate and process the meta-data that was saved in save_meta_data().
         *
         * @since    0.1.0
         * @access   protected
         * @param    int          $post_id   The id of the post whose meta-data is to be validated and processed.
         */
    protected function validate_and_process_data( $post_id ) {

        $post_type = get_post_type($post_id);

        $type = get_post_meta( $post_id, $post_type . '_type', true );
        $number_target_dois = get_post_meta( $post_id, $post_type . '_number_target_dois', true );
        $target_dois = static::get_post_meta_field_containing_array( $post_id, $post_type . '_target_dois');

            // Set the category from $type
        $term_id = term_exists( $type, 'category' );
        if($term_id == 0)
        {
            wp_insert_term( $type, 'category');
            $term_id = term_exists( $type, 'category' );
        }
        wp_set_post_terms( $post_id, $term_id, 'category' );

        $validation_result = '';
        $validation_result .= parent::validate_and_process_data($post_id);

        if ( empty( $number_target_dois ) && $number_target_dois !== '0' ) $validation_result .= "ERROR: Number of target DOIs is empty.\n";

        $settings = O3PO_Settings::instance();
        for ($x = 0; $x < $number_target_dois; $x++) {
            if ( empty( $target_dois[$x] ) )
                $validation_result .= "WARNING: Target DOI " . ($x+1) . " is empty.\n" ;
            else if( substr($target_dois[$x], 0, 8) !== $settings->get_plugin_option('doi_prefix') )
                $validation_result .= "WARNING: Target DOI " . ($x+1) . " does not point to a paper of this publisher or it contains a prefix such as https://dx.doi.org/, which it shouldn't. Pleae check the DOI.\n" ;
        }

        return $validation_result;
    }


        /**
         * Do things when the post is finally published.
         *
         * Is called from save_metabox().
         *
         * @since     0.1.0
         * @access    public
         * @param     int     $post_id     Id of the post that is actually published publicly.
         */
    protected function on_post_actually_published( $post_id ) {

        $validation_result = parent::on_post_actually_published($post_id);

        $post_type = get_post_type($post_id);

        $corresponding_author_has_been_notifed_date = get_post_meta( $post_id, $post_type . '_corresponding_author_has_been_notifed_date', true );
        $corresponding_author_email = get_post_meta( $post_id, $post_type . '_corresponding_author_email', true );
        $type = get_post_meta( $post_id, $post_type . '_type', true );
        $doi = static::get_doi($post_id);
        $doi_suffix = get_post_meta( $post_id, $post_type . '_doi_suffix', true );
        $title = get_post_meta( $post_id, $post_type . '_title', true );
        $journal = get_post_meta( $post_id, $post_type . '_journal', true );
		$post_url = get_permalink( $post_id );

            // Send Emails about the submission to us
        $to = ($this->environment->is_test_environment() ? $this->get_journal_property('developer_email') : $this->get_journal_property('publisher_email'));
        $headers = array( 'From: ' . $this->get_journal_property('publisher_email'));
        $subject  = ($this->environment->is_test_environment() ? 'TEST ' : '') . 'A ' . strtolower($type) . ' has been published/updated by ' . $journal;
        $message  = ($this->environment->is_test_environment() ? 'TEST ' : '') . $journal . " has published/updated the following " . strtolower($type) . ":\n\n";
        $message .= "Title:  " . $title . "\n";
        $message .= "Authos: " . static::get_formated_authors($post_id) . "\n";
        $message .= "URL:    " . $post_url . "\n";
        $message .= "DOI:    " . $this->get_journal_property('doi_url_prefix') . $doi . "\n";

        $successfully_sent = wp_mail( $to, $subject, $message, $headers);

        if(!$successfully_sent)
            $validation_result .= 'WARNING: Error sending email notifation of publication to publisher.' . "\n";

            // Send trackbacks to the arXiv and ourselves
        $number_target_dois = get_post_meta( $post_id, $post_type . '_number_target_dois', true );
        $target_dois = static::get_post_meta_field_containing_array( $post_id, $post_type . '_target_dois');
        for ($x = 0; $x < $number_target_dois; $x++) {
            if( substr($target_dois[$x], 0, 8) === $this->get_journal_property('doi_prefix') )
            {
                $trackback_excerpt = static::get_trackback_excerpt($post_id);
                $suspected_post_url = '/' . $this->targe_publication_type_name_plural . substr($target_dois[$x], 8);
                $target_post_id = url_to_postid($suspected_post_url);
                $target_post_type = get_post_type($target_post_id);
                $target_eprint = get_post_meta( $target_post_id, $target_post_type . '_eprint', true );
                $eprint_without_version = preg_replace('#v[0-9]*$#', '', $target_eprint);
                if(!empty($target_eprint) && !$this->environment->is_test_environment()) {
                        //trachback to the arxiv
                    $trackback_result .= trackback( $this->get_journal_property('arxiv_url_trackback_prefix') . $eprint_without_version , $title, $trackback_excerpt, $post_id );
                }
                    //trachback to ourselves
                trackback( get_site_url() . $suspected_post_url, $title, $trackback_excerpt, $post_id );
            }
        }

            // Send email notifying authors of publication
		if( empty($corresponding_author_has_been_notifed_date) || $this->environment->is_test_environment()) {

            $to = ($this->environment->is_test_environment() ? $this->get_journal_property('developer_email') : $corresponding_author_email);
			$headers = array( 'Cc: ' . ($this->environment->is_test_environment() ? $this->get_journal_property('developer_email') : $this->get_journal_property('publisher_email') ), 'From: ' . $this->get_journal_property('publisher_email'));
			$subject  = ($this->environment->is_test_environment() ? 'TEST ' : '') . $journal . " has published your " . $type;
			$message  = ($this->environment->is_test_environment() ? 'TEST ' : '') . "Dear " . static::get_formated_authors($post_id) . ",\n\n";
			$message .= "Congratulations! Your " . $type . " '" . $title . "' has been published by " . $journal . " and is now available under:\n\n";
			$message .= $post_url . "\n\n";
			$message .= "Your " . $type . " has been assigned the following journal reference and DOI\n\n";
			$message .= "Journal reference: " . static::get_formated_citation($post_id) . "\n";
			$message .= "DOI:               " . $this->get_journal_property('doi_url_prefix') . $doi . "\n\n";
			$message .= "In case you have an ORCID you can go to http://search.crossref.org/?q=" . str_replace('/', '%2F', $doi) . " to conveniently add your new publication to your profile.\n\n";
			$message .= "Please be patient, it can take several hours before the above link works.\n\n";
            $message .= "Thank you for writing this " . $type . " for " . $journal . "!\n\n";
			$message .= "Best regards,\n\n";
			$message .= "Christian, Lídia, and Marcus\n";
			$message .= "Executive Board\n";
			$successfully_sent = wp_mail( $to, $subject, $message, $headers);

            if($successfully_sent) {
                update_post_meta( $post_id, $post_type . '_corresponding_author_has_been_notifed_date', date("Y-m-d") );
            }
            else
            {
                $validation_result .= 'WARNING: Sending email to corresponding author failed.' . "\n";
            }
		}

        return $validation_result;
    }

        /**
         * Get the excerpt for trackbacks.
         *
         * @since    0.1.0
         * @access   private
         * @param    int      $post_id    Id of the post.
         */
    static private function get_trackback_excerpt( $post_id ) {

        $post_type = get_post_type($post_id);
        if ( $post_type === $this->get_publication_type_name() ) {
            $doi = static::get_doi( $post_id );
            $authors = static::get_formated_authors($post_id);
            $excerpt = '';
            $excerpt .= '<h2>' . esc_html($authors) . '</h2>' . "\n";
                //$excerpt .= '<a href="' . $this->get_journal_property('doi_url_prefix') . $doi . '">' . $this->get_journal_property('doi_url_prefix') . $doi . '</a>';
            $excerpt .= static::lead_in_paragraph($post_id) . "\n";
            $excerpt .= '<p>' . get_post_field('post_content', $post_id) . '</p>' . "\n";
            $excerpt = str_replace(']]>', ']]&gt;', $excerpt);
            $bbl = get_post_meta( $post_id, $post_type . '_bbl', true );
            $excerpt = O3PO_Latex::expand_cite_to_html($excerpt, $bbl);
            $excerpt = wp_html_excerpt($excerpt, 552, '&#8230;');
            return $excerpt;
        }
        else
            return '';
    }

        /**
         * Get the excerpt of a this publication type.
         *
         * As we modify the content in get_the_content() we
         * construct the excerpt from stratch,
         *
         * To be added to the 'get_the_excerpt' filter.
         *
         * @since     0.1.0
         * @param     string    $content    Content to be filtered.
         */
    public function get_the_excerpt( $content ) {

        global $post;

        $post_id = $post->ID;
        $post_type = get_post_type($post_id);

        if ( $post_type === $this->get_publication_type_name() ) {
            $content = '';
            $content .= '<p class="authors-in-excerpt">' . static::get_formated_authors( $post_id ) . ',</p>' . "\n";
            $content .= '<p class="citation-in-excerpt">' . static::get_formated_citation($post_id) . ' <a href="' . $this->get_journal_property('doi_url_prefix') . static::get_doi($post_id) . '">' . $this->get_journal_property('doi_url_prefix') . static::get_doi($post_id) . '</a>' . "\n";
            $content .= '<p><a href="' . get_permalink($post_id) . '" class="abstract-in-excerpt">';
            $bbl = get_post_meta( $post_id, $post_type . '_bbl', true );
            $trimmer_abstract = wp_html_excerpt( do_shortcode(O3PO_Latex::expand_cite_to_html(get_post_field('post_content', $post_id), $bbl)), 190, '&#8230;');


            while( preg_match_all('/(?<!\\\\)\$/', $trimmer_abstract) % 2 !== 0 )
            {
                empty($i) ? $i = 1 : $i += 1;
                $trimmer_abstract = wp_html_excerpt( get_post_meta( $post_id, $post_type . '_abstract', true ), 190+$i, '&#8230;');
            }
            $content .= esc_html ( $trimmer_abstract );
            $content .= '</a></p>';
        }

        return $content;
    }

        /**
         * Echo the sub type/category part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected function the_admin_panel_sub_type( $post_id ) {

        $post_type = get_post_type($post_id);

        $type = get_post_meta( $post_id, $post_type . '_type', true );
        echo '	<tr>';
		echo '		<th><label for="' . $post_type . '_type" class="' . $post_type . '_type_label">' . 'Type' . '</label></th>';
		echo '		<td>';
        echo '			<div style="float:left"><select name="' . $post_type . '_type">';
        foreach(static::get_associated_categories() as $current_type)
            echo '<option value="' . $current_type . '"' . ($current_type === $type ? " selected" : "" ) . '>' . $current_type . '</option>';
        echo '</select><br /><label for="' . $post_type . '_type" class="' . $post_type . '_type_label">Type of ' . $this->get_publication_type_name() . '</label></div>';
		echo '		</td>';
		echo '	</tr>';

    }

        /**
         * Echo the target DOI part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected function the_admin_panel_target_dois( $post_id ) {

        $post_type = get_post_type($post_id);

        $number_target_dois = get_post_meta( $post_id, $post_type . '_number_target_dois', true );
        $target_dois = static::get_post_meta_field_containing_array( $post_id, $post_type . '_target_dois');

        if( empty( $number_target_dois ) && $number_target_dois !== '0' )
            $number_target_dois = 1;

        echo '	<tr>';
        echo '		<th><label for="' . $post_type . '_number_target_dois" class="' . $post_type . '_number_target_dois_label">' . 'Number of target dois' . '</label></th>';
		echo '		<td>';
		echo '			<input style="width:4rem" type="number" id="' . $post_type . '_number_target_dois" name="' . $post_type . '_number_target_dois" class="' . $post_type . '_number_target_dois_field required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( $number_target_dois ) . '"><p>(Please put here the total number of other DOIs this ' . $this->get_publication_type_name() . ' is on. To update the number of fields below, please save the post.)</p>';
		echo '		</td>';
		echo '	</tr>';
		for ($x = 0; $x < $number_target_dois; $x++) {
			echo '	<tr>';
			echo '		<th><label for="' . $post_type . '_target_doi" class="' . $post_type . '_target_doi_label">' . "Target doi " . ($x+1) . '</label></th>';
			echo '		<td>';
			echo '			<input type="text" name="' . $post_type . '_target_dois[]" class="' . $post_type . '_target_dois required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($target_dois[$x]) ? $target_dois[$x] : '' ) . '" />';

			echo '		</td>';
			echo '	</tr>';
		}
    }

        /**
         * Echo the reviewers summary part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected static function the_admin_panel_reviewers_summary( $post_id ) {

        $post_type = get_post_type($post_id);

        $reviewers_summary = get_post_meta( $post_id, $post_type . '_reviewers_summary', true );

		if( empty( $reviewers_summary ) ) $reviewers_summary = '' ;

        echo '	<tr>';
		echo '		<th><label for="' . $post_type . '_reviewers_summary" class="' . $post_type . '_reviewers_summary_label">' . 'Reviewers summary' . '</label></th>';
		echo '		<td>';
		echo '			<textarea rows="6" style="width:100%;" name="' . $post_type . '_reviewers_summary" id="' . $post_type . '_reviewers_summary">' . esc_attr__( $reviewers_summary ) . '</textarea><p>(Summary of the reviewers.)</p>';
		echo '		</td>';
		echo '	</tr>';
    }

        /**
         * Echo the reviewers part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected static function the_admin_panel_reviewers( $post_id ) {

        $post_type = get_post_type($post_id);

        $number_reviewers = get_post_meta( $post_id, $post_type . '_number_reviewers', true );
		$reviewer_given_names = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_given_names');
		$reviewer_surnames = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_surnames');
		$reviewer_name_styles = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_name_styles');
		$reviewer_affiliations = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_affiliations');
		$reviewer_orcids = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_orcids');
        $reviewer_urls = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_urls');
        $reviewer_ages = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_ages');
        $reviewer_grades = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_grades');
        if( empty( $number_reviewers ) ) $number_reviewers = static::get_default_number_reviewers();
		if( empty( $reviewer_given_names ) ) $reviewer_given_names = array();
		if( empty( $reviewer_surnames ) ) $reviewer_surnames = array();
		if( empty( $reviewer_name_styles ) ) $reviewer_name_styles = array();
		if( empty( $reviewer_affiliations ) ) $reviewer_affiliations = array();
		if( empty( $reviewer_orcids ) ) $reviewer_orcids = array();
        if( empty( $reviewer_urls ) ) $reviewer_urls = array();
        if( empty( $reviewer_ages ) ) $reviewer_ages = array();
        if( empty( $reviewer_grades ) ) $reviewer_grades = array();

        echo '	<tr>';
		echo '		<th><label for="' . $post_type . '_number_reviewers" class="' . $post_type . '_number_reviewers_label">' . 'Number of reviewers' . '</label></th>';
		echo '		<td>';
		echo '			<input style="width:4rem" type="number" id="' . $post_type . '_number_reviewers" name="' . $post_type . '_number_reviewers" class="' . $post_type . '_number_reviewers_field required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( $number_reviewers ) . '"><p>(Please put here the actual number of reviewers. To update the number of entries in the list below please save the post. Give affiliations as a comma separated list referring to the affiliations below, e.g., 1,2,5,7. As with the title, special characters are allowed and must be entered as í or é and so on.)</p>';
		echo '		</td>';
		echo '	</tr>';

		for ($x = 0; $x < $number_reviewers; $x++) {
			$y = $x+1;
			echo '	<tr>';
			echo '		<th><label for="' . $post_type . '_reviewer" class="' . $post_type . '_reviewer_label">' . "Reviewer  $y" . '</label></th>';
			echo '		<td>';
			echo '			<div style="float:left"><input type="text" name="' . $post_type . '_reviewer_given_names[]" class="' . $post_type . '_reviewer_given_names_field" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_given_names[$x]) ? $reviewer_given_names[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_given_names" class="' . $post_type . '_reviewer_given_names_label">Given name</label></div>';
			echo '			<div style="float:left"><input type="text" name="' . $post_type . '_reviewer_surnames[]" class="' . $post_type . '_reviewer_surnames_field required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_surnames[$x]) ? $reviewer_surnames[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_surnames" class="' . $post_type . '_reviewer_surnames_label">Surname</label></div>';
			echo '			<div style="float:left"><select name="' . $post_type . '_reviewer_name_styles[]">';
			foreach(array("western", "eastern", "islensk", "given-only") as $style)
                echo '<option value="' . $style . '"' . ( (isset($reviewer_name_styles[$x]) && $reviewer_name_styles[$x] === $style) ? " selected" : "" ) . '>' . $style . '</option>';
			echo '</select><br /><label for="' . $post_type . '_reviewer_name_styles" class="' . $post_type . '_reviewer_name_styles_label">Name style</label></div>';
			echo '			<div style="float:left"><input style="width:5rem" type="text" name="' . $post_type . '_reviewer_affiliations[]" class="' . $post_type . '_reviewer_affiliations_field" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_affiliations[$x]) ? $reviewer_affiliations[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_affiliations" class="' . $post_type . '_reviewer_affiliations">Institutions</label></div>';
//			echo '			<div style="float:left"><input style="width:11rem" type="text" name="' . $post_type . '_reviewer_orcids[]" class="' . $post_type . '_reviewer_orcids" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_orcids[$x]) ? $reviewer_orcids[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_orcids" class="' . $post_type . '_reviewer_orcids_label">ORCID</label></div>';
//            echo '			<div style="float:left"><input style="width:20rem" type="text" name="' . $post_type . '_reviewer_urls[]" class="' . $post_type . '_reviewer_urls" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_urls[$x]) ? $reviewer_urls[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_urls" class="' . $post_type . '_reviewer_urls_label">URL</label></div>';
            echo '			<div style="float:left"><input style="width:20rem" type="text" name="' . $post_type . '_reviewer_ages[]" class="' . $post_type . '_reviewer_ages" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_ages[$x]) ? $reviewer_ages[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_ages" class="' . $post_type . '_reviewer_ages_label">Age</label></div>';
            echo '			<div style="float:left"><input style="width:20rem" type="text" name="' . $post_type . '_reviewer_grades[]" class="' . $post_type . '_reviewer_grades" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_grades[$x]) ? $reviewer_grades[$x] : '' ) . '" /><br /><label for="' . $post_type . '_reviewer_grades" class="' . $post_type . '_reviewer_grades_label">Grade</label></div>';
			echo '		</td>';
			echo '	</tr>';
		}

    }

        /**
         * Echo the reviewer institutions part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected static function the_admin_panel_reviewer_institutions( $post_id ) {

        $post_type = get_post_type($post_id);
        $number_reviewer_institutions = get_post_meta( $post_id, $post_type . '_number_reviewer_institutions', true );
		$reviewer_institutions = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_institutions');

        if( empty( $number_reviewer_institutions ) && $number_reviewer_institutions !== '0' ) $number_reviewer_institutions = 1;
		if( empty( $reviewer_institutions ) ) $reviewer_institutions = array();

		echo '	<tr>';
		echo '		<th><label for="' . $post_type . '_number_reviewer_institutions" class="' . $post_type . '_number_reviewer_institutions_label">' . 'Number of reviewer institutions' . '</label></th>';
		echo '		<td>';
		echo '			<input style="width:4rem" type="number" id="' . $post_type . '_number_reviewer_institutions" name="' . $post_type . '_number_reviewer_institutions" class="' . $post_type . '_number_reviewer_institutions_field required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( $number_reviewer_institutions ) . '"><p>(Please put here the total number of reviewer institutions. To update the number of Reviewer instition fields save the post.)</p>';
		echo '		</td>';
		echo '	</tr>';
		for ($x = 0; $x < $number_reviewer_institutions; $x++) {
			$y = $x+1;
			echo '	<tr>';
			echo '		<th><label for="' . $post_type . '_reviewer_institutions" class="' . $post_type . '_reviewer_institutions_label">' . "Reviewer institution  $y" . '</label></th>';
			echo '		<td>';
			echo '			<input style="width:100%" type="text" name="' . $post_type . '_reviewer_institutions[]" class="' . $post_type . '_reviewer_institutions required" placeholder="' . esc_attr__( '', 'qj-plugin' ) . '" value="' . esc_attr__( isset($reviewer_institutions[$x]) ? $reviewer_institutions[$x] : '' ) . '" />';

			echo '		</td>';
			echo '	</tr>';
		}

    }

        /**
         * Echo the author commentary part of the admin panel.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    protected static function the_admin_panel_author_commentary( $post_id ) {

        $post_type = get_post_type($post_id);

        $author_commentary = get_post_meta( $post_id, $post_type . '_author_commentary', true );
        $about_the_author = get_post_meta( $post_id, $post_type . '_about_the_author', true );

		if( empty( $author_commentary ) ) $author_commentary = '' ;
		if( empty( $about_the_author ) ) $about_the_author = '' ;

        echo '	<tr>';
		echo '		<th><label for="' . $post_type . '_author_commentary" class="' . $post_type . '_author_commentary_label">' . 'Author commentary' . '</label></th>';
		echo '		<td>';
		echo '			<textarea rows="6" style="width:100%;" name="' . $post_type . '_author_commentary" id="' . $post_type . '_author_commentary">' . esc_attr__( $author_commentary ) . '</textarea><p>(Commentary of the author(s).)</p>';

        echo '			<textarea rows="6" style="width:100%;" name="' . $post_type . '_about_the_author" id="' . $post_type . '_about_the_author">' . esc_attr__( $about_the_author ) . '</textarea><p>(Some text about the author(s).)</p>';

		echo '		</td>';
		echo '	</tr>';
    }

        /**
         * Get the reviewers summary as html.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    public static function get_reviewers_summary_html( $post_id ) {

        $post_type = get_post_type($post_id);

        $reviewers_summary = get_post_meta( $post_id, $post_type . '_reviewers_summary', true );

        $reviewers_summary_html = '';
        $reviewers_summary_html .= '<h3>Reviewers summary</h3>';
        $reviewers_summary_html .= '<p class="reviewers-summary">' . $reviewers_summary . '</p>';

        return $reviewers_summary_html;
    }

       /**
         * Get the reviewers as html.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    public static function get_reviewers_html( $post_id ) {

        $post_type = get_post_type($post_id);

        $number_reviewers = get_post_meta( $post_id, $post_type . '_number_reviewers', true );
		$reviewer_given_names = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_given_names');
		$reviewer_surnames = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_surnames');
		$reviewer_name_styles = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_name_styles');
		$reviewer_affiliations = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_affiliations');
		$reviewer_orcids = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_orcids');
        $reviewer_urls = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_urls');
        $reviewer_ages = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_ages');
        $reviewer_grades = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_grades');


        $number_institutions = get_post_meta( $post_id, $post_type . '_number_reviewer_institutions', true );
        $reviewer_institutions = static::get_post_meta_field_containing_array( $post_id, $post_type . '_reviewer_institutions');

        $reviewers_html = '';
        $reviewers_html .= '<h3>Reviewed by</h3>';
        $reviewer_names = array();
        for ($x = 0; $x < $number_reviewers; $x++) {
            $reviewer_names[] = $reviewer_given_names[$x] . ' ' . $reviewer_surnames[$x];
        }
        $reviewers_html .= '<p>';
        $reviewers_html .= O3PO_Utility::oxford_comma_implode($reviewer_names) . '<br />';
        if(!empty($reviewer_ages) and !empty($reviewer_grades))
        {
            $reviewer_ages_filtered = array_filter($reviewer_ages,'strlen');
            $reviewer_grades_filtered = array_filter($reviewer_grades,'strlen');
            if(!empty($reviewer_ages_filtered) and !empty($reviewer_grades_filtered))
            {
                $min_age = min($reviewer_ages_filtered);
                $max_age = max($reviewer_ages_filtered);
                $min_grade = min($reviewer_grades_filtered);
                $max_grade = max($reviewer_grades_filtered);
                $reviewers_html .= 'Grade ' . ( ($min_grade === $max_grade)? $max_grade : $min_grade . '&ndash;' . $max_grade ) . ' (' .  ( ($min_age === $max_age) ? 'age ' . $max_age : 'ages ' . $min_age . '&ndash;' . $max_age ) . ')<br />';
            }
        }
        if(!empty($reviewer_institutions))
            $reviewers_html .= O3PO_Utility::oxford_comma_implode($reviewer_institutions) . '<br />';
        $reviewers_html .= "The reviewers consented to publication of their names as stated" . '<br />';
        $reviewers_html .= '</p>';

        return $reviewers_html;
    }

        /**
         * Get the author commentary summary as html.
         *
         * @since    0.1.0
         * @access   protected
         * @param    int     $post_id     Id of the post.
         */
    public static function get_author_commentary_html( $post_id ) {

        $post_type = get_post_type($post_id);

        $author_commentary = get_post_meta( $post_id, $post_type . '_author_commentary', true );
        $about_the_author = get_post_meta( $post_id, $post_type . '_about_the_author', true );

        $author_commentary_html = '';
        $author_commentary_html .= '<h3>Author commentary</h3>';
        $author_commentary_html .= '<p class="author-commentary">' . $author_commentary . '</p>';
        $author_commentary_html .= '<p class="about-the-author">' . $about_the_author . '</p>';

        return $author_commentary_html;
    }

        /**
         * Fake the author.
         *
         * To be added to the 'the_author' filter.
         *
         * @since    0.1.0
         * @access   pulic
         * @param    string    $display_name   Display name to be filtered.
         */
    public function get_the_author( $display_name ) {

        global $post;

        $post_id = $post->ID;
        $post_type = get_post_type($post_id);

        if ( $post_type === $this->get_publication_type_name() ) {
            $journal = get_post_meta( $post_id, $post_type . '_journal', true );
            return $journal;
        }
        else
        {
            return $display_name;
        }
    }


        /**
         * Construct the content.
         *
         * Contrary to posts of primary publication type, we are not using
         * a single template here, but simply output some information
         * alongside the standard content.
         *
         * To be added to the 'the_content' filter.
         *
         * @since     0.1.0
         * @access    public
         * @param     string    $content     Content to be filtered.
         */
    public function get_the_content( $content ) {

        global $post;

        $post_id = $post->ID;
        $post_type = get_post_type($post_id);

        if ( get_post_type($post_id) === $this->get_publication_type_name() ) {
            $old_content = $content;
            $doi = static::get_doi($post_id);
            $authors = static::get_formated_authors($post_id);
            $type = get_post_meta( $post_id, $post_type . '_type', true );
            $number_target_dois = get_post_meta( $post_id, $post_type . '_number_target_dois', true );
            $target_dois = static::get_post_meta_field_containing_array( $post_id, $post_type . '_target_dois');
            $number_authors = get_post_meta( $post_id, $post_type . '_number_authors', true );
            $author_given_names = static::get_post_meta_field_containing_array( $post_id, $post_type . '_author_given_names');
            $author_surnames = static::get_post_meta_field_containing_array( $post_id, $post_type . '_author_surnames');
            $author_urls = static::get_post_meta_field_containing_array( $post_id, $post_type . '_author_urls');
            $author_affiliations = static::get_post_meta_field_containing_array( $post_id, $post_type . '_author_affiliations');
            $affiliations = static::get_post_meta_field_containing_array( $post_id, $post_type . '_affiliations');
            $citation = rtrim(static::get_formated_citation($post_id), '.');
            $journal = get_post_meta( $post_id, $post_type . '_journal', true );

            $content = '';

            if ( has_post_thumbnail( ) ) {
                $content .= '<img src="' . get_the_post_thumbnail_url($post_id) . '" alt="" width="300" height="150" class="alignright size-medium wp-image-1433">';
            }

            $content .= static::lead_in_paragraph($post_id);

            $all_authors_have_same_affiliation = true;
            if ( !empty($author_affiliations) ) {
                foreach($author_affiliations as $author_affiliation) {
                    if( $author_affiliation !== end($author_affiliations) ) {
                        $all_authors_have_same_affiliation = false;
                    break;
                    }
                }
            }

            $content .= "<p><strong>By";
            for ($x = 0; $x < $number_authors; $x++) {
                if( !empty($author_urls[$x]))
                    $content .= ' <a href="' . $author_urls[$x] . '">' . $author_given_names[$x] . ' ' . $author_surnames[$x] . '</a>';
                else
                    $content .= ' ' . $author_given_names[$x] . ' ' . $author_surnames[$x];
                if( !$all_authors_have_same_affiliation && !empty($author_affiliations) && !empty($author_affiliations[$x]) )
                {
                    $content .= ' (';
                    $this_authors_affiliations = preg_split('/,/', $author_affiliations[$x]);
                    $this_authors_affiliations_count = count($this_authors_affiliations);
                    foreach($this_authors_affiliations as $y => $affiliation_num)
                    {
                        $content .= $affiliations[$affiliation_num-1];
                        if( $y < $this_authors_affiliations_count-1 and $this_authors_affiliations_count > 2) $content .= ",";
                        if( $y < $this_authors_affiliations_count-1 ) $content .= " ";
                        if( $y === $this_authors_affiliations_count-2 ) $content .= "and ";
                    }
                    $content .= ')';
                }
                if( $x < $number_authors-1 and $number_authors > 2) $content .= ",";
                if( $x < $number_authors-1 ) $content .= " ";
                if( $x === $number_authors-2 ) $content .= "and ";
            }
            if(!empty($affiliations) && !empty(end($affiliations)) && $all_authors_have_same_affiliation && !empty($author_affiliations) ) {
                $content .= ' (';
                $this_authors_affiliations = preg_split('/,/', $author_affiliations[0]);
                $this_authors_affiliations_count = count($this_authors_affiliations);
                foreach($this_authors_affiliations as $y => $affiliation_num)
                {
                    $content .= $affiliations[$affiliation_num-1];
                    if( $y < $this_authors_affiliations_count-1 and $this_authors_affiliations_count > 2) $content .= ",";
                    if( $y < $this_authors_affiliations_count-1 ) $content .= " ";
                    if( $y === $this_authors_affiliations_count-2 ) $content .= "and ";
                }
                $content .= ')';
            }
            $content .= ".</strong></p>\n";

            $content .= '<table class="meta-data-table">';
            $content .= '<tr><td>Published:</td><td>' . esc_html(static::get_formated_date_published( $post_id )) .  ', ' . static::get_formated_volume_html($post_id) . ', page ' . get_post_meta( $post_id, $post_type . '_pages', true ) . '</td></tr>';
            $content .= '<tr><td>Doi:</td><td><a href="' . esc_attr($this->get_journal_property('doi_url_prefix') . $doi) . '">' . esc_html($this->get_journal_property('doi_url_prefix') . $doi ) . '</a></td></tr>';
            $content .= '<tr><td>Citation:</td><td>' . esc_html($citation) . '</td></tr>';
            $content .= '</table>';
//            $content .= '<a style="display:none;" id="print-btn" class="btn-theme-primary" href="javascript:if(window.print)window.print()">print page</a>';
            $content .= '<form action="javascript:if(window.print)window.print()" method="post">';
            $content .= '<input style="display:none;" id="print-btn" type="submit" value="print page">';
            $content .= '</form>';
            $content .= '<script type="text/javascript">document.getElementById("print-btn").style.display = "inline-block";</script>';//show button only if browser supports java script
            $bbl = get_post_meta( $post_id, $post_type . '_bbl', true );
            $content .= O3PO_Latex::expand_cite_to_html($old_content, $bbl);

            if($type==="Leap")
            {
                $content .= static::get_reviewers_summary_html($post_id);
                $content .= static::get_reviewers_html($post_id);
                $content .= static::get_author_commentary_html($post_id);
            }

            $content .= static::get_bibtex_html($post_id);
            $content .= static::get_bibliography_html($post_id);
            $content .= static::get_license_information($post_id);
            return $content;
        }
        else
            return $content;
    }


        /**
         * Get the lead in paragraph for View publications.
         *
         * @since    0.1.0
         * @access   public
         * @param    int    $post_id    Id of the post.
         */
    public function lead_in_paragraph( $post_id ) {

        $post_type = get_post_type($post_id);
        $type = get_post_meta( $post_id, $post_type . '_type', true );
        $number_target_dois = get_post_meta( $post_id, $post_type . '_number_target_dois', true );
        $target_dois = static::get_post_meta_field_containing_array( $post_id, $post_type . '_target_dois');
        $journal = get_post_meta( $post_id, $post_type . '_journal', true );

        $content = '';
        $content .= '<p><em>This is ' . ( preg_match('/^[hH]?[aeiouAEIOU]/' , $type) ? 'an' : 'a') . ' ' . $type;
        if($type==="Leap")
            $content .= ' &mdash; a popular science article on quantum research written by scientists and reviewed by teenagers &mdash;';

        if($number_target_dois>0)
            $content .= ' on ';
        else
            $content .= ' published in ' . $journal;

        for ($x = 0; $x < $number_target_dois; $x++) {
            if( substr($target_dois[$x], 0, 8) === $this->get_journal_property('doi_prefix') )
            {
                $target_post_id = url_to_postid( '/' . $this->targe_publication_type_name_plural . substr($target_dois[$x], 8) );
                $target_post_type = get_post_type( $target_post_id );
                $target_title = get_post_meta( $target_post_id, $target_post_type . '_title', true );
                $target_authors = static::get_formated_authors($target_post_id);
                $target_citation = static::get_formated_citation($target_post_id);
                $content .= '<a href="' . $this->get_journal_property('doi_url_prefix') . $target_dois[$x] . '">&quot;' . $target_title . '&quot;</a> by ' . $target_authors . ', published in ' . rtrim($target_citation, '.');
                if( $x < $number_target_dois-1 and $number_target_dois > 2) $formated_authors .= ",";
                if( $x < $number_target_dois-1 ) $formated_authors .= " ";
                if( $x === $number_target_dois-2 ) $formated_authors .= "and ";
            }
            else
                $content .= '<a href="' . $this->get_journal_property('doi_url_prefix') . $target_dois[$x] . '">' . $target_dois[$x] . '</a>';
        }
        $content .= ".</em></p>\n";

        return $content;
    }

        /**
         * Output meta tags describing this publication type.
         *
         * @since     0.1.0
         * @access    public
         */
    public function the_meta_tags() {

        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);

        if ( !is_single() || $post_type !== $this->get_publication_type_name())
            return;

        parent::the_meta_tags();
    }

        /**
         * Get the default number of reviewers.
         *
         * @since    0.1.0
         * @access   protected
         */
    protected static function get_default_number_reviewers() {

        return 4;
    }

        /**
         * Get the path of the fulltext pdf.
         *
         * In this class there is nothing to return. So we don't return anything.
         *
         * @since 0.2.0
         * @access    public
         * @param     int     $post_id     Id of the post.
         */
    public function get_fulltext_pdf_path( $post_id ) {

        return null;
    }


        /**
         * Get the pretty permalink of the pdf associated with a post.
         *
         * In this class there is nothing to return. So we don't return anything.
         *
         * @since 0.2.0
         * @access    public
         * @param     int     $post_id     Id of the post.
         */
    public function get_pdf_pretty_permalink( $post_id ) {

        return null;
    }

}
