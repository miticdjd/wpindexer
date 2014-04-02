<div class="updated settings-error" id="wpindexer-manage-index-success-massage" style="display: none;"> 
    <p>
        <strong></strong>
    </p>
</div>

<div class="error settings-error" id="wpindexer-manage-index-error-massage" style="display: none;"> 
    <p>
        <strong></strong>
    </p>
</div>


<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Manage Index</h2>
    <p>Manage elasticsearch index, delete all indexed posts and reindex all posts in elasticsearch.</p>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">Wipe data</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Wipe Data</span></legend>
                            <label for="wipe-data">
                                <span class="wipe-data spinner"></span>
                                <input type="button" value="Wipe data" class="button button-primary" id="wipe-data" name="WipeData">
                                <p class="description">Wipes all informations from the ElasticSearch. (This cannot be undone).</p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Re-index data</th>
                    <td> 
                        <fieldset>
                            <legend class="screen-reader-text"><span>Re-index data</span></legend>
                            <label for="posts">
                                <span class="re-index-data spinner"></span>
                                <input type="button" value="Re-index data" class="button button-primary" id="re-index-data" name="ReIndexData">
                                <p class="description">Re-index all data in elasticsearch server, this can take some time.</p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>