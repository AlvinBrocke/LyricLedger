<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Royalty Transactions</title>
  <link rel="stylesheet" href="/public/css/royalties.css">
</head>
<body>

<h2>Royalty Transactions</h2>
<p class="subheading">Monitor how royalties are flowing. Use the filter to sort by blockchain status or export reports for analysis.</p>

<div class="filter-bar">
  <label for="transaction-filter">Filter by status:</label>
  <select id="transaction-filter" onchange="filterTransaction(this.value)">
    <option value="all">All</option>
    <option value="pending">Pending</option>
    <option value="completed">Completed</option>
    <option value="failed">Failed</option>
  </select>
</div>

<table>
  <thead>
    <tr>
      <th>Transaction Hash</th>
      <th>Artist</th>
      <th>Amount ($)</th>
      <th>Blockchain Status</th>
      <th>Payment Method</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($transactions as $tx): ?>
      <tr class="<?= $tx['blockchain_status'] ?>">
        <td><?= htmlspecialchars($tx['transaction_hash']) ?></td>
        <td><?= htmlspecialchars($tx['artist_name']) ?></td>
        <td><?= number_format($tx['amount'], 2) ?></td>
        <td class="status-label <?= $tx['blockchain_status'] ?>"><?= ucfirst($tx['blockchain_status']) ?></td>
        <td><?= htmlspecialchars($tx['payment_method']) ?></td>
        <td><?= date('M d, Y H:i', strtotime($tx['created_at'])) ?></td>
        <td><a href="/royalties/view/<?= $tx['id'] ?>" class="btn details">Details</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="export-links">
  <a href="/royalties/export/csv">Export CSV</a>
  <a href="/royalties/export/pdf">Export PDF</a>
</div>

<script>
function filterTransaction(status) {
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