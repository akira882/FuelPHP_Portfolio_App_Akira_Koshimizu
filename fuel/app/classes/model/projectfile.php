<?php

class Model_ProjectFile extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'project_id',
        'user_id',
        'filename',
        'filepath',
        'filesize',
        'mimetype',
        'created_at',
        'updated_at',
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );

    protected static $_table_name = 'project_files';

    protected static $_belongs_to = array(
        'project' => array(
            'key_from' => 'project_id',
            'model_to' => 'Model_Project',
            'key_to' => 'id',
        ),
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        )
    );

    /**
     * Get file extension
     * @return string
     */
    public function get_extension()
    {
        return strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
    }

    /**
     * Get file icon based on extension
     * @return string
     */
    public function get_icon()
    {
        $ext = $this->get_extension();

        $icons = array(
            // Documents
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'txt' => '📝',
            // Spreadsheets
            'xls' => '📊',
            'xlsx' => '📊',
            'csv' => '📊',
            // Images
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            // Archives
            'zip' => '🗜️',
            'rar' => '🗜️',
            '7z' => '🗜️',
            // Code
            'php' => '💻',
            'js' => '💻',
            'html' => '💻',
            'css' => '💻',
            'json' => '💻',
        );

        return isset($icons[$ext]) ? $icons[$ext] : '📎';
    }

    /**
     * Format file size
     * @return string
     */
    public function get_formatted_size()
    {
        $bytes = $this->filesize;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
