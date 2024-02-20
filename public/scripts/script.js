function confirmDelete(url) {
  // Affiche une boîte de dialogue de confirmation
  console.log(url)
  var isConfirmed = window.confirm("Êtes-vous sûr de vouloir exécuter cette opération ?");
  if (!isConfirmed) {
    return false;
  }
  window.location.href = url;
  return true;
}

function goBack() {
  window.history.back();
}