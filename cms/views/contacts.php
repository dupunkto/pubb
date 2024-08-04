<header class="bar">
  <h2>Contacts</h2>
  <a href="<?= CMS_CANONICAL ?>/contacts/add" class="button">Add contact</a>
</header>

<?php $contacts = \store\list_contacts() ?>

<ul>
  <?php foreach($contacts as $contact) { ?>
    <li>
      <a href="//<?= $contact['domain'] ?>">@<?= $contact['handle'] ?></a>

      <span class="actions">
        <a href="mailto:<?= $contact['email'] ?>">
          Send message
        </a>
        <a href="<?= CMS_CANONICAL ?>/contacts/edit?id=<?= $contact['id'] ?>">
          Edit
        </a>
      </span>
    </li>
  <?php } ?>
</ul>

<?php if(count($contacts) <= 0) {
  ?>
    <p class="placeholder-text">No contacts yet.</p>
  <?php
} ?>
