<header class="bar">
  <h2>Contacts</h2>
  <a href="<?= CMS_CANONICAL ?>/contacts/add" class="button">Add contact</a>
</header>

<ul>
  <?php foreach(\store\list_contacts() as $contact) { ?>
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
