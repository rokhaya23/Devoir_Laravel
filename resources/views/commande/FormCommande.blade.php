@extends('base')
@extends('template.sidebar')

@section('title', $commande->exists ? 'Modifier une commande' : 'Créer une commande')

@section('content')
    <div class="container mt-lg-5">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col-xl-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <form id="idForm" action="{{ $commande->exists ? route('commande.update', ['commande' => $commande->id]) : route('commande.store') }}" method="POST">
                            @csrf
                            @if ($commande->exists)
                                @method('PUT')
                            @endif

                            <fieldset>
                                <legend class="bg-success">Informations sur le client</legend>
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <div class="mb-3">
                                            <label for="client" class="form-label">Client:</label>
                                            <br>
                                            <br>
                                            <select id="client" name="idClient" class="form-select">
                                                <option value="" selected disabled>Sélectionnez le client</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}" {{ $commande->idClient == $client->id ? 'selected' : '' }}>{{ $client->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-12">
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
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="date_commande" class="form-label">Date de la commande</label>
                                            <input type="date" id="date_commande" name="date_commande" class="form-control datepicker-opens-left" value="{{ $commande->exists ? $commande->date_commande : '' }}">

                                        </div>
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset>
                                <legend class="bg-success">Produits</legend>
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
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody class="products-container">
                                                @foreach ($commande->produits as $produit)
                                                    <tr>
                                                        <td>
                                                            <select class="form-select produit" name="idProduct[]">
                                                                <option value="" selected disabled>Sélectionnez le produit</option>
                                                                @foreach ($produits as $p)
                                                                    <option value="{{ $p->id }}" data-stock="{{ $p->quantite_stock }}" data-prix="{{ $p->prix }}" {{ $produit->id == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control stock" name="stock[]" readonly value="{{ $produit->pivot->quantity }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control quantity" name="quantity[]" min="1" value="{{ $produit->pivot->quantity }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control prix" name="prix[]" readonly value="{{ $produit->prix }}">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-outline-danger remove-product">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <!-- Ligne de produit cachée servant de modèle -->
                                                <tr class="hidden-row">
                                                    <td>
                                                        <select class="form-select produit" name="idProduct[]">
                                                            <option value="" selected disabled>Sélectionnez le produit</option>
                                                            @foreach ($produits as $produit)
                                                                <option value="{{ $produit->id }}" data-stock="{{ $produit->quantite_stock }}" data-prix="{{ $produit->prix }}">{{ $produit->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control stock" name="stock[]" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control prix" name="prix[]" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger remove-product">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="3">
                                                        <!-- Bouton "Ajouter une ligne" -->
                                                        <button type="button" class="btn btn-outline-primary text-nowrap add-new-row">Ajouter une ligne</button>
                                                    </td>
                                                    <td colspan="3">&nbsp;</td>
                                                    <td class="total-column">
                                                        <p class="m-0">Total </p>
                                                        <h5 class="mt-2 text-primary total-amount">F0.00</h5>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row">
                                <div class="col-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">{{ $commande->exists ? "Modifier la commande" : "Valider la commande" }}</button>
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
        $(document).ready(function () {
            // Logique JavaScript pour ajouter et supprimer des lignes de produits
            let produitsContainer = $('.products-container');
            let produitRowTemplate = $('tbody.products-container tr:first').clone();

            $('.add-new-row').on('click', function () {
                let newProduitRow = produitRowTemplate.clone();
                newProduitRow.find('select').val('');
                newProduitRow.find('.stock').val('');
                newProduitRow.find('.quantity').val(1);
                newProduitRow.find('.prix').val('');

                newProduitRow.appendTo(produitsContainer);
            });

            produitsContainer.on('click', '.remove-product', function () {
                $(this).closest('tr').remove();
                updateTotal();
            });

            produitsContainer.on('change', 'select', function () {
                let selectedProduit = $(this).find(':selected');
                let stockInput = $(this).closest('tr').find('.stock');
                let prixInput = $(this).closest('tr').find('.prix');

                // Remplacez les valeurs suivantes par celles appropriées depuis la base de données
                let stock = parseInt(selectedProduit.data('stock'));
                let prix = parseFloat(selectedProduit.data('prix'));

                stockInput.val(stock);
                prixInput.val(prix);

                updateTotal();
            });

            produitsContainer.on('input', '.quantity', function () {
                updateTotal();
            });

            function updateTotal() {
                let total = 0;

                produitsContainer.find('tr').each(function () {
                    let quantity = parseInt($(this).find('.quantity').val());
                    let prix = parseFloat($(this).find('.prix').val());

                    if (!isNaN(quantity) && !isNaN(prix)) {
                        total += quantity * prix;
                    }
                });

                $('.total-amount').text('$' + total.toFixed(2));
            }
        });
    </script>

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

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialiser le datepicker
            $('.datepicker-opens-left').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd', // Mettez à jour le format ici
            });
        });
    </script>

@endsection
