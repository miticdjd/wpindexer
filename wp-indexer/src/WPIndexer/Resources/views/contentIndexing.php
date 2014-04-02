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
                    <strong>Content indexing options saved.</strong>
                </p>
            </div>
        <?php
    }

?>


<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Content indexing</h2>
    <p>Select what type of content you want to index to ElasticSearch.</p>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">Categories</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Categories</span></legend>
                            <label for="categories">
                                <?php 
                                    foreach ($this->data['categories'] as $category) {
                                        ?>
                                            <input type="checkbox" value="<?php echo $category->cat_ID; ?>" id="category-<?php echo $category->cat_ID; ?>" name="categories[]" <?php echo isset($this->values['categories']) && in_array($category->cat_ID, $this->values['categories']) ? 'checked="checked"' : ''; ?>>
                                            <?php echo $category->cat_name; ?>
                                            <br />
                                        <?php
                                    }
                                ?>
                                <p class="description">Select categories you wan to index to ElasticSearch. Only posts from categories you select will be indexed.</p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Posts</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Posts</span></legend>
                            <label for="posts">
                                <?php 
                                    foreach ($this->data['posts'] as $post) {
                                        ?>
                                            <input type="checkbox" value="<?php echo $post; ?>" id="post-<?php echo $post; ?>" name="posts[]" <?php echo (isset($this->values['posts']) ? in_array($post, $this->values['posts']) : false) ? 'checked="checked"' : ''; ?>>
                                            <?php echo $post; ?>
                                            <br />
                                        <?php
                                    }
                                ?>
                                <p class="description">Only type of posts you select will be indexed to ElasticSearch.</p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Fields</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Fields</span></legend>
                            <label for="posts">
                                <?php 
                                    foreach ($this->data['fields'] as $id => $field) {
                                        ?>
                                            <input type="checkbox" value="<?php echo $id; ?>" id="fields-<?php echo $id; ?>" name="fields[]" <?php echo (isset($this->values['fields']) ? in_array($id, $this->values['fields']) : false) ? 'checked="checked"' : ''; ?>>
                                            <?php echo $field; ?>
                                            <br />
                                        <?php
                                    }
                                ?>
                                <p class="description">Only fields you select will be indexed in elastic search.</p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
        </p>
    </form>
</div>