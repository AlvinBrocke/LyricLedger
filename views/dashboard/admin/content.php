<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Content Management</title>
  <link rel="stylesheet" href="../assets/css/content.css">
</head>
<body>

<h2>Content Management</h2>
<p class="subheading">Review and manage uploaded songs. Use the filter to sort by status, approve or reject content, and preview metadata or audio.</p>

<div class="filter-bar">
  <label for="status-filter">Filter by status:</label>
  <select id="status-filter" onchange="filterStatus(this.value)">
    <option value="all">All</option>
    <option value="pending">Pending</option>
    <option value="processed">Processed</option>
    <option value="active">Active</option>
  </select>
</div>

<table>
  <thead>
    <tr>
      <th>Title</th>
      <th>Duration</th>
      <th>Status</th>
      <th>Playback</th>
      <th>Uploaded At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($songs as $song): ?>
      <tr class="<?= $song['status'] ?>">
        <td><?= htmlspecialchars($song['title']) ?></td>
        <td><?= htmlspecialchars($song['duration']) ?>s</td>
        <td class="status-label <?= $song['status'] ?>"><?= ucfirst($song['status']) ?></td>
        <td><audio controls src="<?= htmlspecialchars($song['file_path']) ?>"></audio></td>
        <td><?= date('M d, Y H:i', strtotime($song['created_at'])) ?></td>
        <td>
          <a href="/content/approve/<?= $song['id'] ?>" class="btn approve">Approve</a>
          <a href="/content/reject/<?= $song['id'] ?>" class="btn reject">Reject</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
function filterStatus(status) {
  const rows = document.querySelectorAll("table tbody tr");
  rows.forEach(row => {
    if (status === "all" || row.classList.contains(status)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}
</script>

</body>
</html>