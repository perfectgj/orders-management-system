@extends('layouts.admin')

@section('content')
    <h3>Create Order</h3>

    <div class="card">
        <div class="card-body">

            <form method="POST"
                action="{{ auth()->user()->isAdmin() ? route('admin.orders.store') : route('staff.orders.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Customer</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <table class="table" id="productTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button type="button" id="addRow" class="btn btn-secondary mb-3">+ Add Product</button>

                <h4 class="text-end">Total: ₹<span id="grandTotal">0.00</span></h4>

                <button class="btn btn-success mt-3">Save Order</button>
            </form>

        </div>
    </div>

    <template id="rowTemplate">
        <tr>
            <td>
                <select class="form-select product-select">
                    <option value="">Select Product</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}"
                            data-stock="{{ $p->stock_quantity }}">
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td class="stock-td">-</td>

            <td>
                <input type="number" class="form-control qty" min="1" value="1">
            </td>

            <td class="price-td">0.00</td>

            <td class="subtotal-td">0.00</td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const tbody = document.querySelector("#productTable tbody");
            const template = document.querySelector("#rowTemplate").innerHTML;
            const grandTotalEl = document.getElementById("grandTotal");

            document.getElementById("addRow").onclick = () => {
                tbody.insertAdjacentHTML("beforeend", template);
                updateIndexes();
            };

            function updateIndexes() {
                document.querySelectorAll("#productTable tbody tr").forEach((tr, index) => {
                    tr.querySelector(".product-select").setAttribute("name",
                        `products[${index}][product_id]`);
                    tr.querySelector(".qty").setAttribute("name", `products[${index}][quantity]`);
                });
            }

            document.addEventListener("change", (e) => {
                if (e.target.classList.contains("product-select")) {
                    const tr = e.target.closest("tr");
                    const opt = e.target.selectedOptions[0];

                    if (!opt) return;

                    const price = parseFloat(opt.dataset.price || 0);
                    const stock = parseInt(opt.dataset.stock || 0);

                    tr.querySelector(".stock-td").textContent = stock;
                    tr.querySelector(".price-td").textContent = price.toFixed(2);

                    calculateRow(tr);
                    calculateTotal();
                }
            });

            document.addEventListener("input", (e) => {
                if (e.target.classList.contains("qty")) {
                    const tr = e.target.closest("tr");
                    calculateRow(tr);
                    calculateTotal();
                }
            });

            document.addEventListener("click", (e) => {
                if (e.target.classList.contains("remove-row")) {
                    e.target.closest("tr").remove();
                    updateIndexes();
                    calculateTotal();
                }
            });

            function calculateRow(tr) {
                const price = parseFloat(tr.querySelector(".price-td").textContent || 0);
                const qty = parseInt(tr.querySelector(".qty").value || 0);
                tr.querySelector(".subtotal-td").textContent = (price * qty).toFixed(2);
            }

            function calculateTotal() {
                let sum = 0;
                document.querySelectorAll(".subtotal-td").forEach(td => {
                    sum += parseFloat(td.textContent || 0);
                });
                grandTotalEl.textContent = sum.toFixed(2);
            }

        });
    </script>
@endpush
