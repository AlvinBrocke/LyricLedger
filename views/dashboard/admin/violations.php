<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Violation Reports</title>
  <link rel="stylesheet" href="/public/css/violations.css">
</head>
<body>

<h2>Violation Reports</h2>
<p class="subheading">Review and take action on reported copyright violations. View fingerprint matches, accept or reject claims, and manage resolutions.</p>

<table>
  <thead>
    <tr>
      <th>Reported By</th>
      <th>Detected URL</th>
      <th>Similarity Score</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($violations as $v): ?>
      <tr class="<?= $v['status'] ?>">
        <td><?= htmlspecialchars($v['reported_by']) ?></td>
        <td><a href="<?= htmlspecialchars($v['detected_url']) ?>" target="_blank">View Source</a></td>
        <td><?= htmlspecialchars($v['similarity_score']) ?>%</td>
        <td class="status-label <?= $v['status'] ?>"><?= ucfirst($v['status']) ?></td>
        <td class="action-column">
          <a href="/violations/view/<?= $v['id'] ?>" class="btn view">View</a>
          <a href="/violations/resolve/<?= $v['id'] ?>" class="btn resolve">Resolve</a>
          <a href="/violations/escalate/<?= $v['id'] ?>" class="btn escalate">Escalate</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>