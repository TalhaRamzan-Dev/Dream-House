<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/functions.php';

$props = getAllProperties();

include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Properties</h3>
  <div>
    <a href="add_property.php" class="btn btn-success">+ Add Property</a>
  </div>
</div>

<div class="card p-3">
  <table id="propsTable" class="display table table-striped table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Thumb</th>
        <th>Title</th>
        <th>Type</th>
        <th>Status</th>
        <th>Price</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($props as $p): ?>
        <tr>
          <td><?= esc($p['id']) ?></td>
          <td>
            <?php if (!empty($p['thumbnail'])): ?>
              <img src="../uploads/<?= esc($p['thumbnail']) ?>" class="thumb" alt="">
            <?php endif; ?>
          </td>
          <td><?= esc($p['title']) ?></td>
          <td><?= esc($p['type']) ?></td>
          <td><?= esc($p['status']) ?></td>
          <td><?= number_format($p['price'], 2) ?></td>
          <td><?= esc($p['created_at']) ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="edit_property.php?id=<?= esc($p['id']) ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="delete_property.php?id=<?= esc($p['id']) ?>" onclick="return confirm('Delete this listing?')">Delete</a>
            <?php if ($p['status'] !== 'Sold' && $p['status'] !== 'Rented'): ?>
              <a class="btn btn-sm btn-warning" href="mark_status.php?id=<?= esc($p['id']) ?>&action=sold" onclick="return confirm('Mark as Sold?')">Mark Sold</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
