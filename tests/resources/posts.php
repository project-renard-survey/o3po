<?php

/**
 * The initialy exisitng posts for tests
 *
 * Here we define fake WordPress posts that are used as a basis of the
 * fake WordPress function calls in bootstrap.php
 */


$posts = array(
    1 => array(
        'post_type' => 'paper',
        'post_content' => 'fake_post_content1',
        'paper_nonce' => 'fake_nonce',
        'thumbnail_id' => 2,
        'post_status' => 'private',
        'post_title' => 'Fake title',
        'permalink' => 'Fake permalink',
        'meta' => array(
            'paper_abstract' => 'This is a test abstract that contains not math so far and no special characters.',
            'paper_abstract_mathml' => 'This is a test abstract that contains not math so far and no special characters.',
            'paper_eprint' => '0908.2921v2',
            'paper_eprint_was_changed_on_last_save' => false,
//            'paper_arxiv_pdf_attach_ids' => array(4),
            'paper_arxiv_pdf_attach_ids' => '',
            'paper_popular_summary' => 'Some random fake summary.',
            'paper_feature_image_caption' => 'Some random fake cation.',
            'paper_fermats_library' => 'checked',
            'paper_validation_result' => 'fake_validation_result',
            'paper_title' => 'Fake title',
            'paper_title_mathml' => 'Fake title',
            'paper_corresponding_author_email' => 'mal_formed_corresponding_author_email',
            'paper_corresponding_author_has_been_notifed_date' => '',
            'paper_buffer_email' => 'fake_paper_buffer_email',
            'paper_buffer_email_was_sent_date' => '',
            'paper_buffer_special_text' => 'fake_paper_buffer_special_text',
            'paper_fermats_library_permalink' => 'fake_paper_fermats_library_permalink',
            'paper_fermats_library_permalink_worked' => 'fake_paper_fermats_library_permalink_worked',
            'paper_fermats_library_has_been_notifed_date' => '',
            'paper_number_authors' => 2,
            'paper_author_given_names' => ['Foo', 'Baz'],
            'paper_author_surnames' => ['Bar', 'Boo'],
            'paper_author_name_styles' => ["western", "western"],
            'paper_author_affiliations' => ['1,2','2'],
            'paper_author_orcids' => ['',''],
            'paper_author_urls' => ['',''],
            'paper_number_affiliations' => 2,
            'paper_affiliations' => ['Foo University', 'Bar Institut'],
            'paper_date_published' => '',
            'paper_journal' => 'fake_paper_journal',
            'paper_volume' => '1',
            'paper_pages' => '1',
            'paper_doi_prefix' => 'fake_paper_doi_prefix',
            'paper_doi_suffix' => 'fake_paper_doi_suffix',
            'paper_bbl' => 'fake_paper_bbl',
            'paper_author_latex_macro_definitions' => '',
            'paper_crossref_xml' => 'fake_paper_crossref_xml',
            'paper_crossref_response' => 'fake_paper_crossref_response',
            'paper_doaj_json' => 'fake_paper_doaj_json',
            'paper_doaj_response' => 'fake_paper_doaj_response',
            'paper_arxiv_fetch_results' => 'fake_paper_arxiv_fetch_results',
            'paper_arxiv_source_attach_ids' => '',
            'paper_doi_suffix_was_changed_on_last_save' => false,
            'paper_clockss_xml' => 'fake clocks xml',
            'paper_clockss_response' => 'fake clocks response',
                        ),
               ),
    2 => array(
        'post_type' => 'attachment',
        'attachment_image_src' => 'fake_attachment_image_src',
        'thumbnail_id' => 3,
        'attachment_url' => "Fake attachment_url",
        'meta' => array(),
               ),
    3 => array(
        'post_type' => 'attachment',
        'attachment_image_src' => 'fake_attachment_image_src',
        'attachment_url' => "Fake attachment_url",
        'attachment_path' => dirname(__FILE__) . '/arxiv/0809.2542v4.pdf',
               ),
    4 => array(
        'post_type' => 'attachment',
        'attachment_url' => 'fake_attachment_url',
        'attachment_path' => 'fake_attachment_path',
               ),
    5 => array(
        'post_type' => 'paper',
        'post_content' => 'fake_post_content5',
        'paper_nonce' => 'fake_nonce',
        'thumbnail_id' => 2,
        'post_status' => 'private',
        'post_title' => 'Fake title 2',
        'permalink' => 'Fake permalink',
        'meta' => array(
            'paper_abstract' => 'This is a test abstract 2 that contains not math so far and no special characters.',
            'paper_abstract_mathml' => 'This is a test abstract 2 that contains not math so far and no special characters.',
            'paper_eprint' => '0809.2542v4',
            'paper_eprint_was_changed_on_last_save' => false,
            'paper_arxiv_pdf_attach_ids' => '',
            'paper_popular_summary' => 'Some random fake summary.',
            'paper_feature_image_caption' => 'Some random fake cation.',
            'paper_fermats_library' => '',
            'paper_validation_result' => 'fake_validation_result',
            'paper_title' => 'Fake title 2',
            'paper_title_mathml' => 'Fake title 2',
            'paper_corresponding_author_email' => 'validemail@quantum-journal.org',
            'paper_corresponding_author_has_been_notifed_date' => '',
            'paper_buffer_email' => 'fake_paper_buffer_email',
            'paper_buffer_email_was_sent_date' => '',
            'paper_buffer_special_text' => 'fake_paper_buffer_special_text',
            'paper_fermats_library_permalink' => 'fake_paper_fermats_library_permalink',
            'paper_fermats_library_permalink_worked' => 'fake_paper_fermats_library_permalink_worked',
            'paper_fermats_library_has_been_notifed_date' => '',
            'paper_number_authors' => 1,
            'paper_author_given_names' => ['Foo'],
            'paper_author_surnames' => ['Bar'],
            'paper_author_name_styles' => ["western"],
            'paper_author_affiliations' => ['1'],
            'paper_author_orcids' => ['0000-0003-0290-4698'],
            'paper_author_urls' => [''],
            'paper_number_affiliations' => 1,
            'paper_affiliations' => ['Foo Institute'],
            'paper_date_published' => '',
            'paper_journal' => 'fake_paper_journal',
            'paper_volume' => '1',
            'paper_pages' => '2',
            'paper_doi_prefix' => 'fake_paper_doi_prefix',
            'paper_doi_suffix' => 'fake_paper_doi_suffix',
            'paper_bbl' => 'fake_paper_bbl',
            'paper_author_latex_macro_definitions' => '',
            'paper_crossref_xml' => 'fake_paper_crossref_xml',
            'paper_crossref_response' => 'fake_paper_crossref_response',
            'paper_doaj_json' => 'fake_paper_doaj_json',
            'paper_doaj_response' => 'fake_paper_doaj_response',
            'paper_arxiv_fetch_results' => 'fake_paper_arxiv_fetch_results',
            'paper_arxiv_source_attach_ids' => '',
            'paper_doi_suffix_was_changed_on_last_save' => false,
            'paper_clockss_xml' => '',
            'paper_clockss_response' => '',
                        )
               ),
    6 => array(
        'post_type' => 'attachment',
        'attachment_url' => 'fake_attachment_url',
        'attachment_path' => dirname(__FILE__) . '/arxiv/0809.2542v4.pdf',
               ),
    7 => array(
        'post_type' => 'attachment',
        'attachment_url' => 'fake_attachment_url',
        'attachment_path' => dirname(__FILE__) . '/arxiv/0809.2542v4.tar.gz',
               ),
    8 => array(
        'post_type' => 'paper',
        'post_content' => 'fake_post_content8',
        'paper_nonce' => 'fake_nonce',
        'thumbnail_id' => 2,
        'post_status' => 'publish',
        'post_title' => 'Fake title',
        'permalink' => 'Fake permalink',
        'meta' => array(
            'paper_abstract' => 'This is a test abstract that contains not math so far and no special characters.',
            'paper_abstract_mathml' => 'This is a test abstract that contains not math so far and no special characters.',
            'paper_eprint' => '0908.2921v2',
            'paper_eprint_was_changed_on_last_save' => false,
            'paper_arxiv_pdf_attach_ids' => array(3),
            'paper_popular_summary' => 'Some random fake summary.',
            'paper_feature_image_caption' => 'Some random fake cation.',
            'paper_fermats_library' => '',
            'paper_validation_result' => 'fake_validation_result',
            'paper_title' => 'Fake title',
            'paper_title_mathml' => 'Fake title',
            'paper_corresponding_author_email' => 'mal_formed_corresponding_author_email',
            'paper_corresponding_author_has_been_notifed_date' => '',
            'paper_buffer_email' => '',
            'paper_buffer_email_was_sent_date' => '',
            'paper_buffer_special_text' => 'fake_paper_buffer_special_text',
            'paper_fermats_library_permalink' => 'fake_paper_fermats_library_permalink',
            'paper_fermats_library_permalink_worked' => 'fake_paper_fermats_library_permalink_worked',
            'paper_fermats_library_has_been_notifed_date' => '',
            'paper_number_authors' => 2,
            'paper_author_given_names' => ['Foo', 'Baz'],
            'paper_author_surnames' => ['Bar', 'Boo'],
            'paper_author_name_styles' => ["western", "western"],
            'paper_author_affiliations' => ['1,2','2'],
            'paper_author_orcids' => ['',''],
            'paper_author_urls' => ['',''],
            'paper_number_affiliations' => 2,
            'paper_affiliations' => ['Foo University', 'Bar Institut'],
            'paper_date_published' => current_time("Y-m-d"),
            'paper_journal' => 'fake_paper_journal',
            'paper_volume' => '2',
            'paper_pages' => '3',
            'paper_doi_prefix' => 'fake_paper_doi_prefix',
            'paper_doi_suffix' => 'fake_journal_level_doi_suffix-' . current_time("Y-m-d") . '-3',
            'paper_bbl' => 'fake_paper_bbl',
            'paper_author_latex_macro_definitions' => '',
            'paper_crossref_xml' => 'fake_paper_crossref_xml',
            'paper_crossref_response' => 'fake_paper_crossref_response',
            'paper_doaj_json' => 'fake_paper_doaj_json',
            'paper_doaj_response' => 'fake_paper_doaj_response',
            'paper_arxiv_fetch_results' => 'fake_paper_arxiv_fetch_results',
            'paper_arxiv_source_attach_ids' => array(4),
            'paper_doi_suffix_was_changed_on_last_save' => false,
            'paper_clockss_xml' => '',
            'paper_clockss_response' => '',
                        ),
               ),
    9 => array(
        'post_type' => 'view',
        'post_content' => 'fake_post_content9',
        'paper_nonce' => 'fake_nonce',
        'thumbnail_id' => 2,
        'post_status' => 'publish',
        'post_title' => 'Fake title',
        'permalink' => 'Fake permalink',
        'meta' => array(
            'view_type' => 'Leap',
            'view_number_target_dois' => '0',
            'view_title' => 'A leaping title',
            'view_corresponding_author_email' => 'author@leap.me',
            'view_buffer_email' => 'checked',
            'view_number_authors' => '1',
            'view_number_affiliations' => '1',
            'view_date_published' => current_time("Y-m-d"),
            'view_doi_prefix' => 'fake',
            'view_reviewers_summary' => 'A nice summary.',
            'view_number_reviewers' => '2',
            'view_number_reviewer_institutions' => 1,
            'view_author_commentary' => 'A not so nice reply.',
            'view_validation_result' => '',
            'view_target_dois' => '',
            'view_title_mathml' => '',
            'view_corresponding_author_has_been_notifed_date' => '',
            'view_buffer_email_was_sent_date' => '',
            'view_author_given_names' => array('Foo'),
            'view_affiliations' => array('Affiliation'),
            'view_journal' => '',
            'view_doi_suffix' => '',
            'view_reviewer_given_names' => array('A.', 'B.'),
            'view_reviewer_institutions' => array('A School', 'B School'),
            'view_about_the_author' => 'Some text about the author',
            'view_buffer_special_text' => 'Special buffer message',
            'view_author_surnames' => array('Bar'),
            'view_volume' => '1',
            'view_reviewer_surnames' => array('Aaaaaa', 'Bbbbbb'),
            'view_author_name_styles' => array('western', 'western'),
            'view_pages' => '1',
            'view_reviewer_name_styles' => array('western', 'western'),
            'view_author_affiliations' => '',
            'view_reviewer_affiliations' => '',
            'view_author_orcids' => array('', ''),
            'view_reviewer_orcids' => array('', ''),
            'view_author_urls' => array('', ''),
            'view_reviewer_urls' => array('', ''),
            'view_reviewer_ages' => array('6', '8'),
            'view_reviewer_grades' => array('3', '4'),
            'view_bbl' => '',
            'view_author_latex_macro_definitions' => '',
            'view_crossref_xml' => '',
            'view_crossref_response' => '',
            'view_doaj_json' => '',
            'view_doaj_response' => '',
            'view_doi_suffix_was_changed_on_last_save' => false,
            'view_abstract' => '',
            'view_clockss_xml' => '',
            'view_clockss_response' => '',
                        )
               ),
    10 => array(
        'post_type' => 'view',
        'post_content' => 'fake_post_content10',
        'paper_nonce' => 'fake_nonce',
        'thumbnail_id' => 2,
        'post_status' => 'publish',
        'post_title' => 'Fake title',
        'permalink' => 'Fake permalink',
        'meta' => array(
            'view_type' => 'Leap',
            'view_number_target_dois' => '1',
            'view_title' => 'A leaping title',
            'view_corresponding_author_email' => 'author@leap.me',
            'view_buffer_email' => 'checked',
            'view_number_authors' => '1',
            'view_number_affiliations' => '1',
            'view_date_published' => current_time("Y-m-d"),
            'view_doi_prefix' => 'fake',
            'view_reviewers_summary' => 'A nice summary.',
            'view_number_reviewers' => '2',
            'view_number_reviewer_institutions' => 1,
            'view_author_commentary' => 'A not so nice reply.',
            'view_validation_result' => '',
            'view_target_dois' => array('a-doi-that-does-not-exist'),
            'view_title_mathml' => '',
            'view_corresponding_author_has_been_notifed_date' => '',
            'view_buffer_email_was_sent_date' => '',
            'view_author_given_names' => array('Baz'),
            'view_affiliations' => array('Foo Instiute'),
            'view_journal' => '',
            'view_doi_suffix' => '',
            'view_reviewer_given_names' => array('C.', 'D.'),
            'view_reviewer_institutions' => array('CD School'),
            'view_about_the_author' => 'Some text about the author',
            'view_buffer_special_text' => 'Special buffer message',
            'view_author_surnames' => array('Bazzz'),
            'view_volume' => '1',
            'view_reviewer_surnames' => array('Ccccc', 'Ddddd'),
            'view_author_name_styles' => array('western'),
            'view_pages' => '2',
            'view_reviewer_name_styles' => array('western', 'western'),
            'view_author_affiliations' => array('1'),
            'view_reviewer_affiliations' => array('1', '1'),
            'view_author_orcids' => array(''),
            'view_reviewer_orcids' => array('',''),
            'view_author_urls' => array('http://www.baz.baz'),
            'view_reviewer_urls' => array('',''),
            'view_reviewer_ages' => array('12','12'),
            'view_reviewer_grades' => array('8','9'),
            'view_bbl' => '',
            'view_author_latex_macro_definitions' => '',
            'view_crossref_xml' => '',
            'view_crossref_response' => '',
            'view_doaj_json' => '',
            'view_doaj_response' => '',
            'view_doi_suffix_was_changed_on_last_save' => false,
            'view_abstract' => '',
            'view_clockss_xml' => '',
            'view_clockss_response' => '',
                        )
                ),
               );
