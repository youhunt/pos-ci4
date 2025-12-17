<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .pos-item-input { width: 70px; }
    .pos-price { width: 110px; }
</style>

<div class="row">
    <!-- LEFT: POS -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <h5>POS / Kasir</h5>
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSearch">
                    Cari Produk
                </button>
            </div>

            <div class="card-body">

                <!-- BARCODE INPUT -->
                <div class="mb-3">
                    <input autofocus id="barcode" type="text" class="form-control form-control-lg"
                        placeholder="Scan Barcode / Ketik SKU lalu Enter">
                </div>

                <!-- ITEM TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="cart-table">
                        <thead class="table-dark">
                            <tr>
                                <th style="width:35%">Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body"></tbody>
                    </table>
                </div>

                <div id="cart-items"></div>

                <div class="cart-summary mt-3 p-3 border rounded bg-light">
                    <div class="d-flex justify-content-between">
                        <span>Total Sebelum Diskon</span>
                        <strong id="total-before"></strong>
                    </div>

                    <div class="d-flex justify-content-between text-danger">
                        <span>Total Diskon</span>
                        <strong id="total-discount"></strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-4">
                        <span>Total Bayar</span>
                        <strong id="total-after"></strong>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- RIGHT: TOTAL -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <h4>Total: <span id="total-view">0</span></h4>

                <div class="mb-2">
                    <label>Bayar</label>
                    <input id="pay" type="number" class="form-control form-control-lg" />
                </div>

                <div class="mb-2">
                    <label>Kembalian</label>
                    <input id="change" type="text" class="form-control form-control-lg" readonly />
                </div>

                <button id="btn-save" class="btn btn-primary w-100 btn-lg mt-3">
                    Simpan Transaksi
                </button>

            </div>
        </div>
    </div>

</div>

<!-- MODAL SEARCH PRODUK -->
<div class="modal fade" id="modalSearch">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        
            <div class="modal-header">
                <h5 class="modal-title">Cari Produk</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input id="search-input" type="text" class="form-control mb-3" placeholder="Ketik nama produk...">

                <div class="table-responsive">
                    <table class="table table-hover" id="search-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="search-body"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
// ========================
// GLOBAL CART (from server)
// ========================
let CART = {
    items: {},   // <-- INI WAJIB OBJECT BUKAN ARRAY
    total_before: 0,
    total_discount: 0,
    total_after: 0
};

// ========================
// Helper Format
// ========================
function rupiah(x) {
    return Number(x).toLocaleString('id-ID');
}

async function loadCart() {
    let res = await fetch("/pos/get-cart");
    CART = await res.json();

    renderCart();
}

document.addEventListener("DOMContentLoaded", loadCart);

// ================================
// 1) Add item → ALWAYS via server
// ================================
async function addToCart(productId, qty = 1) {
    let fd = new FormData();
    fd.append('product_id', productId);
    fd.append('qty', qty);

    let res = await fetch("/pos/add-to-cart", {
        method: "POST",
        body: fd
    });

    loadCart();
}

// ================================
// 2) Render Cart from server data
// ================================
function renderCart() {
    if (!CART.items || typeof CART.items !== 'object') {
        CART.items = {};
    }

    let html = '';

    Object.entries(CART.items).forEach(([productId, item]) => {
        html += `
        <tr>
            <td>
                ${item.name} 
                ${item.discount_label ? `<br><small class="text-danger">${item.discount_label}</small>` : ''}
            </td>

            <td class="pos-price">
                <span class="${item.discount_total > 0 ? 'text-decoration-line-through text-muted' : ''}">
                    ${rupiah(item.price)}
                </span>

                ${item.price_after < item.price ? 
                    `<br><span class="text-success fw-bold">${rupiah(item.price_after)}</span>` 
                : ''}
            </td>

            <td>
                <input type="number" class="form-control pos-item-input qty-input" 
                       data-id="${productId}" value="${item.qty}">
            </td>

            <td>${rupiah(item.subtotal_after)}</td>

            <td>
                <button class="btn btn-danger btn-sm btn-remove" data-id="${productId}">
                    X
                </button>
            </td>
        </tr>`;
    });

    $("#cart-body").html(html);
    $("#total-view").text(rupiah(CART.total_after));
    $("#total-before").text(rupiah(CART.total_before));
    $("#total-discount").text(rupiah(CART.total_discount));
    $("#total-after").text(rupiah(CART.total_after));

    calculateChange();
}

// ================================
// 3) Update Qty → server update
// ================================
$(document).on("change", ".qty-input", async function() {
    const id = $(this).data("id");
    const qty = $(this).val();

    let fd = new FormData();
    fd.append('product_id', id);
    fd.append('qty', qty);

    let res = await fetch("/pos/update-cart", {
        method: "POST",
        body: fd
    });

    CART = await res.json();
    renderCart();
});

// ================================
// 4) Remove from Cart
// ================================
$(document).on("click", ".btn-remove", async function() {
    const id = $(this).data("id");

    let fd = new FormData();
    fd.append('product_id', id);

    let res = await fetch("/pos/remove-item", {
        method: "POST",
        body: fd
    });

    loadCart();
});

// ================================
// 5) Barcode Scanner
// ================================
$("#barcode").keypress(function(e) {
    if (e.which === 13) {
        const code = $(this).val();
        $(this).val('');

        if (!code) return;

        $.getJSON("/pos/get-by-barcode/" + code, function(res) {
            if (!res) return alert("Produk tidak ditemukan!");
            addToCart(res.id, 1);
        });
    }
});

// ================================
// 6) Search Produk Modal
// ================================
function loadSearch(q = "") {
    $.getJSON("/pos/search?q=" + q, function(list) {
        let html = '';
        list.forEach(p => {
            html += `
            <tr>
                <td>${p.name}</td>
                <td>${rupiah(p.price)}</td>
                <td>${p.stock}</td>
                <td><button data-id="${p.id}" class="btn btn-primary btn-add-search">Pilih</button></td>
            </tr>`;
        });
        $("#search-body").html(html);
    });
}

$("#search-input").keyup(function() {
    loadSearch($(this).val());
});

$("#modalSearch").on("shown.bs.modal", function () {
    $("#search-input").val("");
    loadSearch("");
});

$(document).on("click", ".btn-add-search", function() {
    const id = $(this).data("id");

    $.getJSON("/pos/get/" + id, function(res) {
        addToCart(res.id);
        $("#modalSearch").modal("hide");
    });
});

// ================================
// 7) Hitung Kembalian
// ================================
$("#pay").keyup(function() {
    calculateChange();
});

function calculateChange() {
    let bayar = parseFloat($("#pay").val() || 0);
    let kembali = bayar - CART.total_after;
    $("#change").val(rupiah(kembali < 0 ? 0 : kembali));
}

// ================================
// 8) SAVE TRANSACTION
// ================================
$("#btn-save").click(async function() {
    if (!CART.items || typeof CART.items !== 'object') {
        CART.items = {};
    }

    let fd = new FormData();
    fd.append('total_paid', $("#pay").val());
    fd.append('total_amount', CART.total_after);
    
    let res = await fetch("/pos/save", {
        method: "POST",
        body: fd
    });

    let json = await res.json();

    if (json.success) {
        alert("Transaksi berhasil!");
        CART = { items: [], total_after: 0 };
        renderCart();
        $("#pay").val('');
        $("#barcode").focus();
    }
});

</script>

<?= $this->endSection() ?>

