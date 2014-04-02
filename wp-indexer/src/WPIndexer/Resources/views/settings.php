<?php

    if (count($this->errors) > 0)
    {
        foreach ($this->errors as $error) {
            ?>
                <div class="error settings-error" id="setting-error-invalid_admin_email"> 
                    <p>
                        <strong><?php echo $error; ?></strong>
                    </p>
                </div>
            <?php
        }
    }
    
    if ($this->success) {
        ?>
            <div class="updated settings-error" id="setting-error-settings_updated"> 
                <p>
                    <strong>Settings saved.</strong>
                </p>
            </div>
        <?php
    }

?>


<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Settings</h2>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">Enable Search</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Enable Search</span></legend>
                            <label for="enable_search">
                                <input type="checkbox" value="1" id="enable_search" name="enable_search" <?php echo isset($this->values['enable_search']) ? 'checked="checked"' : ''; ?>>
                                If enabled, the default wordpress search will use WPIndexer
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="server_url">Server url</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo isset($this->values['server_url']) ? $this->values['server_url'] : ''; ?>" id="server_url" name="server_url">
                        <p class="description">Server URL of ElasticSearch</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="index_name">Index name</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo isset($this->values['index_name']) ? $this->values['index_name'] : ''; ?>" id="index_name" name="index_name">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="read_timeout">Read timeout</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo isset($this->values['read_timeout']) ? $this->values['read_timeout'] : ''; ?>" id="read_timeout" name="read_timeout">
                        <p class="description">The maximum time (in seconds) that read request should wait for server response if the call times out, wordpress will fallback to standard search</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="write_timeout">Write timeout</label>
                    </th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo isset($this->values['write_timeout']) ? $this->values['write_timeout'] : ''; ?>" id="write_timeout" name="write_timeout">
                        <p class="description">The maximum time (in seconds) that write request should wait for server response. This is time for indexing single post/page.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
        </p>
    </form>
</div>