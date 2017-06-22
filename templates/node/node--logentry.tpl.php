<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if ((!$page && !empty($title)) || !empty($title_prefix) || !empty($title_suffix) || $display_submitted): ?>
  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page && !empty($title)): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>

    <?php if($display_submitted): ?>
      <p class="author-datetime">
      <?php printf("Lognumber %s.",l($lognumber,"node/$node->nid")); ?>
      <?php print "  $logentry_submitted.  "; ?>
      </p>
      <?php if($updated): ?>
      <p class="last-update">
      <?php print "$updated"; ?>
      </p>
      <?php endif; ?>
  <?php endif; ?>
  </header>
  <?php endif; ?>

  <?php if (isset($comment_count) && $comment_count > 0) : ?>
      <?php if ($comment_count == 1) : ?>
        <a href="#comments-wrapper" data-toggle="collapse">There is 1 comment</a>
      <?php else : ?>
        <a href="#comments-wrapper" data-toggle="collapse">There are <?php print $comment_count; ?> comments</a>
      <?php endif; ?>
  <div id="comments-wrapper" class="collapse in">
    <?php print $comments; ?>
  </div>
  <?php endif; ?>
  
  <table class="field-vitals">
      <tr><th>Logbooks: </th><td><?php print render($logbooks);?></td></tr>
      <?php if($tags): ?>
        <tr><th>Tags: </th><td><?php print render($tags);?></td></tr>
      <?php endif; ?>
      <?php if($entrymakers): ?>
      <tr><th>Entry Makers: </th><td><?php print render($entrymakers);?></td></tr>
      <?php endif; ?>
      <?php if($references): ?>
      <tr><th>References: </th><td><?php print render($references);?></td></tr>
      <?php endif; ?>
      <?php if($backlinks): ?>
      <tr><th>Backlinks: </th><td><?php print render($backlinks);?></td></tr>
      <?php endif; ?>
      <?php if($extern_ref): ?>
      <tr><th>External References: </th><td><?php print render($extern_ref);?></td></tr>
      <?php endif; ?>
      <?php if($files): ?>
      <tr><th>Attached Files: </th><td><?php print render($files);?></td></tr>
      <?php endif; ?>

    </table>

  <div<?php print $content_attributes; ?>>
    <?php
        print $body;
    ?>
  </div>


  <div class="attachment-box">
      <?php  print $images; ?>
  </div>

<?php if ($view_mode == 'full') : ?>
  <?php if ($uid === 0) : ?>
      <h3 class="panel-title">
      <a href="/user/login?destination=node/<?php print $node->nid;?>">Log in to comment..</a>
      </h3>
  <?php else: ?>
    <h3 class="panel-title">
      <a href="#comment-panel" data-toggle="collapse" >Add a comment..</a>
    </h3>
    <div class="panel"> 
      <div id="comment-panel" class="panel-collapse collapse">
         <?php print $comment_form; ?>
      </div>
   </div>
  <?php endif; ?>
<?php endif; ?>
</article>
