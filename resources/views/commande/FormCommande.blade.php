@extends('base')
@extends('template.sidebar')

@section('title', 'Créer une commande')

@section('content')
    <div class="container mt-lg-5">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col-xl-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <form id="idForm" action="{{ route('commande.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label for="client" class="form-label"></label>
                                        <br>
                                        <br>
                                        <select id="client" name="idClient" class="form-select">
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
                                        <input type="date" id="date_commande" name="date_commande"
                                               class="form-control datepicker-opens-left">
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
                                                <th>Stock</th>
                                                <th>Quantité</th>
                                                <th>Prix Unitaire</th>
                                                <th>Prix Total</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody class="products-container">
                                            <tr>
                                                <td>
                                                    <select id="produit" class="form-select" name="idProduct[]">
                                                        <option value="" selected disabled>Sélectionnez le produit</option>
                                                        @foreach ($produits as $produit)
                                                            <option value="{{ $produit->id }}" >{{ $produit->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="stock" name="stock" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"  name="quantity[]" min="1" value="1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="prix" name="prix[]" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="total_amount[]" readonly>
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

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('client').addEventListener('change', function () {
            var customerId = this.value;
            axios.get(`/orders/customer/${customerId}`)
                .then(function (response) {
                    var customerDetails = response.data;
                    document.getElementById('adresse').value = customerDetails.adresse;
                    document.getElementById('telephone').value = customerDetails.telephone;
                    document.getElementById('sexe').value = customerDetails.sexe;
                })
                .catch(function (error) {
                    console.error('Une erreur s\'est produite lors de la récupération des détails du client :', error);
                });
        });
    </script>

    <script>
        document.getElementById('produit').addEventListener('change', function () {
            var customer = this.value;
            axios.get(`/orders/product/${customer}`)
                .then(function (response) {
                    var customerProduct = response.data;
                    document.getElementById('stock').value = customerProduct.quantite_stock;
                    document.getElementById('prix').value = customerProduct.prix;
                })
                .catch(function (error) {
                    console.error('Une erreur s\'est produite lors de la récupération des détails du client :', error);
                });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Initialiser le datepicker
            $('.datepicker-opens-left').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd', // Mettez à jour le format ici
            });

            // Fonction pour calculer le total d'un produit
            function calculateProductTotal(row) {
                var quantity = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
                var price = parseFloat(row.querySelector('input[name="prix[]"]').value) || 0;

                var total = quantity * price;
                row.querySelector('input[name="total_amount[]"]').value = total.toFixed(2);

                updateTotalAmount(); // Mettez à jour le total général après avoir calculé le total d'un produit
            }



            // Mettre à jour le prix unitaire et le prix total lors du choix d'un produit
            $('.products-container').on('change', 'select[name="idProduct[]"]', function () {
                updateProductInfo($(this));
                calculateProductTotal($(this).closest('tr'));
            });

// Mettre à jour le prix total lorsqu'une quantité change
            $('.products-container').on('input', 'input[name="quantity[]"]', function () {
                updateTotalAmount();
                calculateProductTotal($(this).closest('tr'));
            });

            // Ajouter une nouvelle ligne de produit
            $('.add-new-row').click(function () {
                var newRow = $('.products-container tr:first').clone();
                newRow.find('select, input').val('');
                newRow.find('input[name="total_amount[]"]').val('0.00');
                newRow.find('.remove-product').show();
                $('.products-container').append(newRow);
            });

            // Supprimer une ligne de produit
            $('.products-container').on('click', '.remove-product', function () {
                var rowCount = $('.products-container tr').length;
                if (rowCount > 1) {
                    $(this).closest('tr').remove();
                    updateTotalAmount();
                }
            });

            // Formulaire de soumission
            $('form').submit(function () {
                $('.products-container tr').each(function () {
                    if ($(this).find('select[name="idProduct[]"]').val() === '') {
                        $(this).remove();
                    }
                });
            });

            // Fonction pour mettre à jour le total de la commande
            function updateTotalAmount() {
                var totalAmount = 0;
                $('input[name="total_amount[]"]').each(function () {
                    totalAmount += parseFloat($(this).val()) || 0;
                });
                $('.total-amount').text('$' + totalAmount.toFixed(2));
            }
        });
    </script>

@endsection
