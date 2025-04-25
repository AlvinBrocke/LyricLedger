<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>
  <link rel="stylesheet" href="/public/css/users.css">
</head>
<body>

<h2>User Management</h2>
<p class="subheading">Manage registered users, approve new artists, reset accounts, and monitor uploads and earnings.</p>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['full_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><span class="badge role-<?= strtolower($user['role']) ?>"><?= ucfirst($user['role']) ?></span></td>
        <td class="action-column">
          <div class="btn-group">
            <a href="/users/view/<?= $user['id'] ?>" class="btn view">View Activity</a>
            <?php if(strtolower($user['role']) === 'artist'): ?>
              <a href="/users/approve/<?= $user['id'] ?>" class="btn approve">Approve</a>
            <?php endif; ?>
            <a href="/users/reset/<?= $user['id'] ?>" class="btn reset">Reset</a>
            <a href="/users/delete/<?= $user['id'] ?>" class="btn revoke">Revoke</a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>