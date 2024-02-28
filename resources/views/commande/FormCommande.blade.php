@extends('base')
@extends('template.sidebar ')

@section('title', 'Créer une commande')

@section('content')
    <div class="container mt-lg-5">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col-xl-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <form action="{{ route('commande.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="client" class="form-label">Client</label>
                                        <select id="client" name="client_id" class="form-select">
                                            <option value="" selected disabled>Sélectionnez le client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="adresse" class="form-label">Adresse</label>
                                        <input type="text" id="adresse" name="adresse" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="text" id="telephone" name="telephone" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sexe" class="form-label">Sexe</label>
                                        <input type="text" id="sexe" name="sexe" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_commande" class="form-label">Date de la commande</label>
                                        <input type="text" id="date_commande" name="date_commande" class="form-control datepicker-opens-left">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Quantité</th>
                                                <th>Prix Unitaire</th>
                                                <th>Prix Total</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody class="products-container">
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="produit_id[]">
                                                        <option value="" selected disabled>Sélectionnez le produit</option>
                                                        @foreach ($produits as $produit)
                                                            <option value="{{ $produit->id }}" data-price="{{ $produit->prix }}" data-stock="{{ $produit->quantite_stock }}">{{ $produit->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantite[]" min="1" value="1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="prix_unitaire[]" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="prix_total[]" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger remove-product">Supprimer</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                                <td>
                                                    <p class="m-0">Total</p>
                                                    <h5 class="mt-2 text-primary total-amount">$0.00</h5>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-primary text-nowrap add-new-row">Ajouter une ligne</button>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">Valider la commande</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialiser le datepicker
            $('.datepicker-opens-left').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });

            // Mettre à jour les informations du client lorsque vous choisissez un client
            $('#client').change(function() {
                var selectedClient = $(this).find(':selected');
                $('#adresse').val(selectedClient.data('adresse'));
                $('#telephone').val(selectedClient.data('telephone'));
                $('#sexe').val(selectedClient.data('sexe'));
            });

            // Mettre à jour le prix unitaire et le prix total lorsque vous choisissez un produit
            $('.products-container').on('change', 'select[name="produit_id[]"]', function() {
                var selectedProduit = $(this).find(':selected');
                var price = selectedProduit.data('price');
                var stock = selectedProduit.data('stock');
                var row = $(this).closest('tr');
                row.find('input[name="prix_unitaire[]"]').val(price.toFixed(2));
                row.find('input[name="prix_total[]"]').val((price * row.find('input[name="quantite[]"]').val()).toFixed(2));

                // Mettre à jour le stock maximum autorisé
                row.find('input[name="quantite[]"]').attr('max', stock);

                // Calculer le nouveau total
                updateTotalAmount();
            });

            // Mettre à jour le prix total lorsqu'une quantité change
            $('.products-container').on('input', 'input[name="quantite[]"]', function() {
                var row = $(this).closest('tr');
                var price = row.find('select[name="produit_id[]"]').find(':selected').data('price');
                var stock = row.find('select[name="produit_id[]"]').find(':selected').data('stock');
                var quantity = $(this).val();
                if (quantity > stock) {
                    $(this).val(stock);
                    quantity = stock;
                }
                row.find('input[name="prix_total[]"]').val((price * quantity).toFixed(2));

                // Calculer le nouveau total
                updateTotalAmount();
            });

            // Ajouter une nouvelle ligne de produit
            $('.add-new-row').click(function() {
                var newRow = $('.products-container tr:first').clone();
                newRow.find('select, input').val('');
                newRow.find('input[name="prix_total[]"]').val('0.00');
                newRow.find('.remove-product').show();
                $('.products-container').append(newRow);

                // Mettre à jour le prix unitaire et le prix total lorsque vous choisissez un produit
                $('.products-container').on('change', 'select[name="produit_id[]"]', function() {
                    var selectedProduit = $(this).find(':selected');
                    var price = selectedProduit.data('price');
                    var stock = selectedProduit.data('stock');
                    var row = $(this).closest('tr');
                    row.find('input[name="prix_unitaire[]"]').val(price.toFixed(2));
                    row.find('input[name="prix_total[]"]').val((price * row.find('input[name="quantite[]"]').val()).toFixed(2));

                    // Mettre à jour le stock maximum autorisé
                    row.find('input[name="quantite[]"]').attr('max', stock);

                    // Calculer le nouveau total
                    updateTotalAmount();
                });

                // Mettre à jour le prix total lorsqu'une quantité change
                $('.products-container').on('input', 'input[name="quantite[]"]', function() {
                    var row = $(this).closest('tr');
                    var price = row.find('select[name="produit_id[]"]').find(':selected').data('price');
                    var stock = row.find('select[name="produit_id[]"]').find(':selected').data('stock');
                    var quantity = $(this).val();
                    if (quantity > stock) {
                        $(this).val(stock);
                        quantity = stock;
                    }

                    // Décrémenter la quantité en stock
                    selectedProduit.data('stock', stock - quantity);

                    row.find('input[name="prix_total[]"]').val((price * quantity).toFixed(2));

                    // Calculer le nouveau total
                    updateTotalAmount();
                });
            });

            // Supprimer une ligne de produit
            $('.products-container').on('click', '.remove-product', function() {
                var rowCount = $('.products-container tr').length;
                if (rowCount > 1) {
                    $(this).closest('tr').remove();

                    // Calculer le nouveau total
                    updateTotalAmount();
                }
            });

            // Fonction pour mettre à jour le total de la commande
            function updateTotalAmount() {
                var totalAmount = 0;
                $('input[name="prix_total[]"]').each(function() {
                    totalAmount += parseFloat($(this).val()) || 0;
                });
                $('.total-amount').text('$' + totalAmount.toFixed(2));
            }
        });
    </script>
@endsection
