<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    show: Boolean,
    product: { type: Object, default: null },
    tiers: { type: Array, default: () => [] },
    saving: Boolean,
});

const emit = defineEmits(["close", "save"]);

const productCode = ref("");
const productName = ref("");
const category = ref("");
const price = ref("");
const productTier = ref("");

watch(
    () => [props.show, props.product],
    ([open, product]) => {
        if (!open) return;

        productCode.value = product?.product_code ?? "";
        productName.value = product?.name ?? "";
        category.value = product?.category ?? "";
        price.value =
            product?.price != null ? String(Number(product.price)) : "";
        productTier.value = product?.tier_id ? String(product.tier_id) : "";
    },
    { immediate: true },
);

function submit() {
    const code = productCode.value.trim();
    const name = productName.value.trim();

    if (!code || !name || !productTier.value) return;

    emit("save", {
        id: props.product?.id ?? null,
        product_code: code,
        name,
        category: category.value.trim() || null,
        price: price.value === "" ? null : Number(price.value),
        tier_id: Number(productTier.value),
    });
}
</script>

<template>
    <div v-if="show" class="modal-backdrop" @click.self="emit('close')">
        <section
            class="detail-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="product-modal-title"
        >
            <div class="modal-heading">
                <div class="heading-icon icon-purple">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <div>
                    <h2 id="product-modal-title">
                        {{ product ? "Edit Produk" : "Tambah Produk" }}
                    </h2>
                    <p>Isi data produk di bawah ini.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    aria-label="Tutup"
                    @click="emit('close')"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form class="modal-form" @submit.prevent="submit">
                <label class="field">
                    <span>Kode Produk <b>*</b></span>
                    <input
                        v-model="productCode"
                        type="text"
                        maxlength="255"
                        placeholder="Contoh: PRD-016"
                        required
                    />
                </label>

                <label class="field">
                    <span>Nama Produk <b>*</b></span>
                    <input
                        v-model="productName"
                        type="text"
                        placeholder="Masukkan nama produk"
                        required
                    />
                </label>

                <div class="modal-form-grid">
                    <label class="field">
                        <span>Kategori</span>
                        <input
                            v-model="category"
                            type="text"
                            placeholder="Contoh: Viennoiserie"
                        />
                    </label>

                    <label class="field">
                        <span>Harga (IDR)</span>
                        <input
                            v-model="price"
                            type="number"
                            min="0"
                            placeholder="Contoh: 28000"
                        />
                    </label>
                </div>

                <label class="field">
                    <span>Tier Produk <b>*</b></span>
                    <select v-model="productTier" required>
                        <option value="" disabled>Pilih Tier Toko</option>
                        <option value="1">Tier A</option>
                        <option value="2">Tier B</option>
                        <option value="3">Tier C</option>
                    </select>
                </label>

                <button
                    class="save-button modal-done"
                    type="submit"
                    :disabled="saving"
                >
                    <i
                        :class="
                            saving
                                ? 'fa-solid fa-spinner fa-spin'
                                : 'fa-solid fa-floppy-disk'
                        "
                    ></i>
                    {{ saving ? "Menyimpan..." : "Simpan Produk" }}
                </button>
            </form>
        </section>
    </div>
</template>

<style scoped>
.modal-form {
    display: flex;
    flex-direction: column;
    gap: 17px;
}

.modal-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 520px) {
    .modal-form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
