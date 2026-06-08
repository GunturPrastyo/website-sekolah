<script setup>
import { ref, computed, onMounted } from "vue";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhUser,
  PhEnvelopeSimple,
  PhLinkedinLogo,
  PhX,
  PhCaretDown,
  PhCheck,
} from "@phosphor-icons/vue";
import ImageUploader from "@/components/admin/ImageUploader.vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";
import api from "@/api/index.js";

// Kategori Staf
const categories = [
  { id: "pimpinan", name: "Pimpinan Sekolah" },
  { id: "pendidik", name: "Tenaga Pendidik" },
  { id: "kependidikan", name: "Tenaga Kependidikan" },
];

// Saran Jabatan/Posisi Umum
const commonRoles = ref([]);

const staffList = ref([]);

const fetchStaff = async () => {
  try {
    const response = await api.get("/api/staff");
    staffList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data staf:", error);
    triggerToast("Gagal", "Gagal mengambil data staf dari server.", "error");
  }
};

onMounted(() => {
  fetchStaff();
});

const form = ref({
  id: null,
  name: "",
  role: "",
  category: "pimpinan",
  nip: "",
  email: "",
  linkedin: "",
  image: "",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");

const showNewRoleInput = ref(false);
const newRoleName = ref("");
const isRoleDropdownOpen = ref(false);

// State Bulk Action
const selectedStaff = ref([]);
const isBulkEditModalOpen = ref(false);
const isBulkDeleteModalOpen = ref(false);
const bulkEditForm = ref({ category: "", role: "" });

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const resetForm = () => {
  form.value = {
    id: null,
    name: "",
    role: "",
    category: "pimpinan",
    nip: "",
    email: "",
    linkedin: "",
    image: "",
  };
  isEditing.value = false;
  showNewRoleInput.value = false;
  newRoleName.value = "";
  isRoleDropdownOpen.value = false;
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  if (!form.value.name || !form.value.role || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama, Jabatan, dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    await api.post("/api/staff", form.value);
    await fetchStaff();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Berhasil Ditambahkan", "Data staf baru telah ditambahkan ke sistem.");
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat menyimpan data.", "error");
  }
};

const startEdit = (staff) => {
  isEditing.value = true;
  form.value = { ...staff };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.role || !form.value.category) {
    triggerToast("Gagal Menyimpan", "Nama, Jabatan, dan Kategori wajib diisi!", "error");
    return;
  }

  try {
    await api.put(`/api/staff/${form.value.id}`, form.value);
    await fetchStaff();
    isFormVisible.value = false;
    document.body.style.overflow = "";
    triggerToast("Perubahan Disimpan", "Data staf berhasil diperbarui.");
    resetForm();
  } catch (error) {
    console.error(error);
    triggerToast("Gagal Menyimpan", "Terjadi kesalahan saat memperbarui data.", "error");
  }
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/staff/${itemToDelete.value}`);
      await fetchStaff();
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Data staf berhasil dihapus dari sistem.", "info");
    } catch (error) {
      console.error(error);
      triggerToast("Gagal Menghapus", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }
  isDeleteModalOpen.value = false;
};

const cancelDelete = () => {
  itemToDelete.value = null;
  isDeleteModalOpen.value = false;
};

// --- Fungsi Bulk Action ---
const selectAll = computed({
  get: () =>
    selectedStaff.value.length > 0 &&
    selectedStaff.value.length === filteredStaff.value.length,
  set: (val) => {
    if (val) {
      selectedStaff.value = filteredStaff.value.map((s) => s.id);
    } else {
      selectedStaff.value = [];
    }
  },
});

const openBulkEditModal = () => {
  bulkEditForm.value = { category: "", role: "" };
  isBulkEditModalOpen.value = true;
  document.body.style.overflow = "hidden";
};

const closeBulkEditModal = () => {
  isBulkEditModalOpen.value = false;
  document.body.style.overflow = "";
};

const executeBulkEdit = async () => {
  const payload = {};
  if (bulkEditForm.value.category) payload.category = bulkEditForm.value.category;
  if (bulkEditForm.value.role) payload.role = bulkEditForm.value.role;

  if (Object.keys(payload).length === 0) {
    triggerToast("Info", "Tidak ada perubahan yang dipilih.", "info");
    return;
  }

  try {
    for (const id of selectedStaff.value) {
      const staff = staffList.value.find((s) => s.id === id);
      if (staff) {
        const updateData = { ...staff, ...payload };
        await api.put(`/api/staff/${id}`, updateData);
      }
    }
    await fetchStaff(); // Reload data setelah update
    selectedStaff.value = [];
    closeBulkEditModal();
    triggerToast("Berhasil", "Data terpilih berhasil diperbarui.", "success");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal", "Terjadi kesalahan saat memperbarui data massal.", "error");
  }
};

const confirmBulkDelete = () => {
  isBulkDeleteModalOpen.value = true;
};

const cancelBulkDelete = () => {
  isBulkDeleteModalOpen.value = false;
};

const executeBulkDelete = async () => {
  try {
    await Promise.all(selectedStaff.value.map((id) => api.delete(`/api/staff/${id}`)));
    await fetchStaff(); // Reload data setelah hapus
    selectedStaff.value = [];
    isBulkDeleteModalOpen.value = false;
    triggerToast("Berhasil", "Data terpilih berhasil dihapus.", "success");
  } catch (error) {
    console.error(error);
    triggerToast("Gagal", "Terjadi kesalahan saat menghapus data massal.", "error");
    isBulkDeleteModalOpen.value = false;
  }
};

const filteredStaff = computed(() => {
  if (!searchQuery.value) return staffList.value;
  const query = searchQuery.value.toLowerCase();
  return staffList.value.filter(
    (staff) =>
      staff.name.toLowerCase().includes(query) ||
      staff.role.toLowerCase().includes(query) ||
      staff.nip.toLowerCase().includes(query)
  );
});

const groupedStaff = computed(() => {
  return categories
    .map((cat) => ({
      ...cat,
      staff: filteredStaff.value.filter((s) => s.category === cat.id),
    }))
    .filter((cat) => cat.staff.length > 0);
});

const getCategoryName = (id) => {
  const category = categories.find((c) => c.id === id);
  return category ? category.name : id;
};

const selectRole = (role) => {
  if (role === "ADD_NEW") {
    showNewRoleInput.value = true;
    form.value.role = "";
  } else {
    form.value.role = role;
  }
  isRoleDropdownOpen.value = false;
};

const addNewRole = () => {
  const role = newRoleName.value.trim();
  if (role) {
    if (!commonRoles.value.includes(role)) {
      commonRoles.value.push(role);
    }
    form.value.role = role;
    showNewRoleInput.value = false;
    newRoleName.value = "";
  } else {
    triggerToast("Gagal", "Nama jabatan baru tidak boleh kosong!", "error");
  }
};

const cancelNewRole = () => {
  showNewRoleInput.value = false;
  newRoleName.value = "";
  form.value.role = "";
};

const editingRoleIndex = ref(null);
const editingRoleName = ref("");

const startEditRole = (index, role) => {
  editingRoleIndex.value = index;
  editingRoleName.value = role;
};

const saveEditRole = (index) => {
  const newName = editingRoleName.value.trim();
  if (newName) {
    const oldName = commonRoles.value[index];
    staffList.value.forEach((staff) => {
      if (staff.role === oldName) {
        staff.role = newName;
      }
    });
    if (form.value.role === oldName) {
      form.value.role = newName;
    }
    commonRoles.value[index] = newName;
    editingRoleIndex.value = null;
    editingRoleName.value = "";
    triggerToast("Berhasil", "Nama jabatan berhasil diperbarui.", "success");
  } else {
    triggerToast("Gagal", "Nama jabatan tidak boleh kosong!", "error");
  }
};

const cancelEditRole = () => {
  editingRoleIndex.value = null;
  editingRoleName.value = "";
};

const handleDeleteRole = (index) => {
  const roleToDelete = commonRoles.value[index];
  const isInUse = staffList.value.some((s) => s.role === roleToDelete);

  if (isInUse) {
    triggerToast(
      "Gagal",
      `Jabatan "${roleToDelete}" sedang digunakan oleh staf!`,
      "error"
    );
    return;
  }

  // Alert konfirmasi bawaan browser (native confirm)
  if (window.confirm(`Apakah Anda yakin ingin menghapus jabatan '${roleToDelete}'?`)) {
    commonRoles.value.splice(index, 1);
    if (form.value.role === roleToDelete) {
      form.value.role = "";
    }
    triggerToast(
      "Jabatan Dihapus",
      `Jabatan "${roleToDelete}" berhasil dihapus.`,
      "info"
    );
  }

  isRoleDropdownOpen.value = false;
};
</script>

<template>
  <main class="flex-1 overflow-y-auto px-6 md:px-10 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 dark:text-white"
          style="font-family: 'Oswald', sans-serif"
        >
          Manajemen Guru & Staf
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola data tenaga pendidik dan tenaga kependidikan sekolah.
        </p>
      </div>
      <button
        v-if="!isFormVisible"
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Data Staf
      </button>
    </div>

    <!-- Modal Form Tambah/Edit Data -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isFormVisible"
        class="fixed inset-0 z-[100] overflow-y-auto bg-black/50 backdrop-blur-sm"
        @click="hideForm"
      >
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
          <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-4xl flex flex-col transform transition-all relative"
            @click.stop
          >
            <!-- Modal Header -->
            <div
              class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 rounded-t-xl"
            >
              <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                {{ isEditing ? "Edit Data Staf" : "Tambah Data Staf Baru" }}
              </h3>
              <button
                @click="hideForm"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
              >
                <PhX class="w-6 h-6" />
              </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 flex-1">
              <form id="staffForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                  <!-- Kolom Kiri: Foto -->
                  <div class="lg:col-span-1">
                    <ImageUploader
                      v-model="form.image"
                      label="Foto Profil"
                      :isCircular="true"
                      containerClass="w-full max-w-[240px] mx-auto lg:mx-0"
                      imageClass="object-cover object-top"
                    />
                    <p
                      class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center lg:text-left max-w-[240px] mx-auto lg:mx-0"
                    >
                      Rasio foto terbaik adalah 1:1 (persegi).
                    </p>
                  </div>

                  <!-- Kolom Kanan: Form Fields -->
                  <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Nama Lengkap & Gelar</label
                      >
                      <input
                        type="text"
                        v-model="form.name"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="Contoh: Budi Santoso, S.Pd"
                      />
                    </div>

                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Nomor Induk Pegawai (NIP)</label
                      >
                      <input
                        type="text"
                        v-model="form.nip"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="Kosongkan jika tidak ada"
                      />
                    </div>

                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Kategori</label
                      >
                      <select
                        v-model="form.category"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                      >
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                          {{ cat.name }}
                        </option>
                      </select>
                    </div>

                    <div class="md:col-span-2">
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Jabatan / Posisi</label
                      >
                      <div v-show="!showNewRoleInput" class="relative">
                        <!-- Custom Dropdown Button -->
                        <button
                          type="button"
                          @click="isRoleDropdownOpen = !isRoleDropdownOpen"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-left focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center transition-colors"
                          :class="
                            form.role
                              ? 'text-gray-900 dark:text-white'
                              : 'text-gray-400 dark:text-slate-500'
                          "
                        >
                          <span class="truncate">{{
                            form.role || "Pilih jabatan..."
                          }}</span>
                          <PhCaretDown
                            class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                            :class="{ 'rotate-180': isRoleDropdownOpen }"
                          />
                        </button>

                        <!-- Overlay untuk menutup dropdown jika klik di luar -->
                        <div
                          v-if="isRoleDropdownOpen"
                          @click="isRoleDropdownOpen = false"
                          class="fixed inset-0 z-40"
                        ></div>

                        <!-- Custom Dropdown List -->
                        <Transition
                          enter-active-class="transition ease-out duration-100"
                          enter-from-class="opacity-0 translate-y-[-10px]"
                          enter-to-class="opacity-100 translate-y-0"
                          leave-active-class="transition ease-in duration-100"
                          leave-from-class="opacity-100 translate-y-0"
                          leave-to-class="opacity-0 translate-y-[-10px]"
                        >
                          <div
                            v-if="isRoleDropdownOpen"
                            class="absolute top-full z-50 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar"
                          >
                            <ul class="py-1 text-sm">
                              <li
                                v-for="(role, index) in commonRoles"
                                :key="index"
                                class="hover:bg-blue-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 transition-colors group"
                              >
                                <div
                                  v-if="editingRoleIndex === index"
                                  class="flex items-center gap-2 w-full px-4 py-2"
                                  @click.stop
                                >
                                  <input
                                    type="text"
                                    v-model="editingRoleName"
                                    class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-slate-600 rounded bg-white dark:bg-slate-700 focus:ring-blue-500 focus:border-blue-500"
                                    @keydown.enter.prevent="saveEditRole(index)"
                                    @keydown.esc.prevent="cancelEditRole()"
                                  />
                                  <button
                                    type="button"
                                    @click="saveEditRole(index)"
                                    class="p-1 text-green-600 hover:bg-green-100 rounded"
                                    title="Simpan"
                                  >
                                    <PhCheck class="w-4 h-4" />
                                  </button>
                                  <button
                                    type="button"
                                    @click="cancelEditRole()"
                                    class="p-1 text-gray-500 hover:bg-gray-200 rounded"
                                    title="Batal"
                                  >
                                    <PhX class="w-4 h-4" />
                                  </button>
                                </div>
                                <div
                                  v-else
                                  class="flex items-center justify-between w-full px-4 py-2.5 cursor-pointer"
                                  @click="selectRole(role)"
                                >
                                  <span class="truncate pr-2">{{ role }}</span>
                                  <div
                                    class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 shrink-0"
                                    @click.stop
                                  >
                                    <button
                                      type="button"
                                      @click="startEditRole(index, role)"
                                      class="p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors"
                                      title="Edit Jabatan"
                                    >
                                      <PhPencilSimple class="w-4 h-4" />
                                    </button>
                                    <button
                                      type="button"
                                      @click="handleDeleteRole(index)"
                                      class="p-1 text-red-600 hover:bg-red-100 rounded transition-colors"
                                      title="Hapus Jabatan"
                                    >
                                      <PhTrash class="w-4 h-4" />
                                    </button>
                                  </div>
                                </div>
                              </li>
                              <li
                                @click="selectRole('ADD_NEW')"
                                class="px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-slate-700 cursor-pointer font-semibold text-blue-600 dark:text-blue-400 border-t border-gray-100 dark:border-slate-700 transition-colors sticky bottom-0 bg-white dark:bg-slate-800"
                              >
                                + Tambah Jabatan Baru...
                              </li>
                            </ul>
                          </div>
                        </Transition>
                      </div>
                      <div v-show="showNewRoleInput" class="flex gap-2">
                        <input
                          type="text"
                          v-model="newRoleName"
                          class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-slate-500"
                          placeholder="Ketik nama jabatan baru..."
                          @keydown.enter.prevent="addNewRole"
                        />
                        <button
                          type="button"
                          @click="addNewRole"
                          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                        >
                          Simpan
                        </button>
                        <button
                          type="button"
                          @click="cancelNewRole"
                          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-slate-600 dark:text-gray-300 dark:hover:bg-slate-500 transition-colors text-sm font-medium"
                        >
                          Batal
                        </button>
                      </div>
                    </div>

                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Email (Opsional)</label
                      >
                      <input
                        type="email"
                        v-model="form.email"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="Contoh: guru@sekolah.sch.id"
                      />
                    </div>

                    <div>
                      <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >Profil LinkedIn (Opsional)</label
                      >
                      <input
                        type="url"
                        v-model="form.linkedin"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="Contoh: https://linkedin.com/in/username"
                      />
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- Modal Footer -->
            <div
              class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3 rounded-b-xl"
            >
              <button
                type="button"
                @click="hideForm"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
              >
                <PhXCircle class="w-5 h-5 mr-2" />
                Batal
              </button>
              <button
                form="staffForm"
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
              >
                <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
                <PhPlusCircle v-else class="w-5 h-5 mr-2" />
                {{ isEditing ? "Simpan Perubahan" : "Simpan Data" }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Search & List Section -->
    <div
      class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm"
    >
      <!-- Kolom Pencarian & Select All -->
      <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative max-w-md w-full">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
          >
            <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
          </div>
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Cari berdasarkan nama, nip, atau jabatan..."
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors placeholder-gray-400 dark:placeholder-slate-500"
          />
        </div>
        <div class="flex items-center gap-2" v-if="filteredStaff.length > 0">
          <input
            type="checkbox"
            id="selectAll"
            v-model="selectAll"
            .indeterminate="selectedStaff.length > 0 && selectedStaff.length < filteredStaff.length"
            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4"
          />
          <label
            for="selectAll"
            class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer font-medium select-none"
            >Pilih Semua</label
          >
        </div>
      </div>

      <!-- Bulk Actions Bar -->
      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div
          v-if="selectedStaff.length > 0"
          class="bg-blue-50/90 dark:bg-blue-900/30 backdrop-blur-md border border-blue-200 dark:border-blue-800/50 p-3 sm:p-4 flex flex-wrap justify-between items-center gap-4 relative z-20 shadow-sm mb-6 rounded-lg"
        >
          <div class="flex items-center gap-3">
            <span
              class="inline-flex items-center justify-center bg-blue-600 text-white w-7 h-7 rounded-full text-xs font-bold shadow-md"
            >
              {{ selectedStaff.length }}
            </span>
            <span class="text-sm font-semibold text-blue-900 dark:text-blue-200">
              Staf Terpilih
            </span>
          </div>

          <div class="flex items-center gap-2 sm:gap-3">
            <button
              @click="openBulkEditModal"
              class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-700 bg-white dark:bg-slate-800 text-sm font-semibold rounded-lg text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 focus:outline-none transition-colors shadow-sm"
            >
              <PhPencilSimple class="w-4 h-4 sm:mr-2" />
              <span class="hidden sm:inline">Edit Massal</span>
            </button>
            <button
              @click="confirmBulkDelete"
              class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-800/50 bg-white dark:bg-slate-800 text-sm font-semibold rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-slate-700 focus:outline-none transition-colors shadow-sm"
            >
              <PhTrash class="w-4 h-4 sm:mr-2" />
              <span class="hidden sm:inline">Hapus</span>
            </button>
            <div
              class="h-6 border-l-2 border-blue-200 dark:border-blue-700/50 mx-1 sm:mx-2"
            ></div>
            <button
              @click="selectedStaff = []"
              class="inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-gray-900 hover:bg-white dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700 focus:outline-none transition-all"
              title="Batal Pilih"
            >
              <PhX class="w-5 h-5" />
            </button>
          </div>
        </div>
      </Transition>

      <!-- Empty State -->
      <div
        v-if="filteredStaff.length === 0"
        class="py-12 text-center text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl"
      >
        <p v-if="searchQuery">Tidak ada staf yang cocok dengan "{{ searchQuery }}".</p>
        <p v-else>Belum ada data guru/staf yang ditambahkan.</p>
      </div>

      <!-- Grouped Grid Cards -->
      <div v-else class="space-y-10">
        <div v-for="group in groupedStaff" :key="group.id">
          <div class="flex items-center mb-6">
            <h3
              class="text-xl font-bold text-gray-800 dark:text-white shrink-0 pr-4"
              style="font-family: 'Oswald', sans-serif"
            >
              {{ group.name }}
            </h3>
            <div class="flex-1 h-px bg-gray-200 dark:bg-slate-700"></div>
          </div>

          <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
          >
            <div
              v-for="staff in group.staff"
              :key="staff.id"
              class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col items-center text-center relative group"
              :class="{
                'ring-2 ring-blue-500 shadow-md': selectedStaff.includes(staff.id),
              }"
            >
              <!-- Checkbox Item -->
              <label
                class="absolute top-3 left-3 z-20 cursor-pointer flex items-center justify-center w-6 h-6 rounded-md bg-white/90 dark:bg-slate-800/90 shadow-sm border border-gray-200 dark:border-slate-600 opacity-0 group-hover:opacity-100 transition-opacity"
                :class="{
                  'opacity-100 !border-blue-500': selectedStaff.includes(staff.id),
                }"
              >
                <input
                  type="checkbox"
                  :value="staff.id"
                  v-model="selectedStaff"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                />
              </label>

              <!-- Dropdown Aksi (Muncul saat hover) -->
              <div
                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur p-1 rounded-lg border border-gray-100 dark:border-slate-700 z-10"
              >
                <button
                  @click="startEdit(staff)"
                  class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-md transition-colors"
                  title="Edit"
                >
                  <PhPencilSimple class="w-4 h-4" />
                </button>
                <button
                  @click="deleteEntry(staff.id)"
                  class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-slate-700 rounded-md transition-colors"
                  title="Hapus"
                >
                  <PhTrash class="w-4 h-4" />
                </button>
              </div>

              <!-- Foto -->
              <div
                class="w-20 h-20 mb-4 shrink-0 rounded-full border-2 border-gray-100 dark:border-slate-600 overflow-hidden bg-gray-50 dark:bg-slate-700 flex items-center justify-center"
              >
                <img
                  v-if="staff.image"
                  :src="staff.image"
                  :alt="staff.name"
                  class="w-full h-full object-cover"
                />
                <PhUser v-else class="w-8 h-8 text-gray-400" />
              </div>

              <!-- Info -->
              <h4
                class="font-bold text-gray-900 dark:text-white line-clamp-1 mb-1"
                :title="staff.name"
              >
                {{ staff.name }}
              </h4>
              <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-2">
                {{ staff.role }}
              </p>

              <div
                class="mt-auto pt-4 border-t border-gray-100 dark:border-slate-700 w-full flex flex-col gap-1.5"
              >
                <!-- Tautan Kontak -->
                <div
                  v-if="staff.email || staff.linkedin"
                  class="flex justify-center gap-2 mb-1"
                >
                  <a
                    v-if="staff.email"
                    :href="'mailto:' + staff.email"
                    class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                    title="Kirim Email"
                  >
                    <PhEnvelopeSimple class="w-5 h-5" />
                  </a>
                  <a
                    v-if="staff.linkedin"
                    :href="staff.linkedin"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                    title="Profil LinkedIn"
                  >
                    <PhLinkedinLogo class="w-5 h-5" />
                  </a>
                </div>

                <div
                  class="text-xs text-gray-500 dark:text-gray-400 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 px-2.5 py-1.5 rounded"
                >
                  <span>NIP:</span>
                  <span class="font-semibold text-gray-700 dark:text-gray-300">{{
                    staff.nip || "-"
                  }}</span>
                </div>
                <div
                  class="text-xs text-gray-500 dark:text-gray-400 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50 px-2.5 py-1.5 rounded"
                >
                  <span>Kategori:</span>
                  <span class="font-semibold text-gray-700 dark:text-gray-300">{{
                    getCategoryName(staff.category)
                  }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Data Staf"
      message="Apakah Anda yakin ingin menghapus data staf ini? Tindakan ini tidak dapat dikembalikan."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />

    <!-- Modal Bulk Edit -->
    <Transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-300"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isBulkEditModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="closeBulkEditModal"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all"
          @click.stop
        >
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              Edit Massal ({{ selectedStaff.length }} Staf)
            </h3>
            <button
              @click="closeBulkEditModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>
          <div class="p-6">
            <p class="text-sm text-gray-500 mb-4">
              Pilih data yang ingin diubah. Kosongkan jika tidak ingin mengubah.
            </p>
            <div class="space-y-4">
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Kategori</label
                >
                <select
                  v-model="bulkEditForm.category"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">-- Tetap --</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>
              <div>
                <label
                  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >Jabatan / Posisi</label
                >
                <select
                  v-model="bulkEditForm.role"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">-- Tetap --</option>
                  <option v-for="role in commonRoles" :key="role" :value="role">
                    {{ role }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              @click="closeBulkEditModal"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              @click="executeBulkEdit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk class="w-5 h-5 mr-2" />
              Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Modal Bulk Delete -->
    <ConfirmModal
      :isOpen="isBulkDeleteModalOpen"
      title="Hapus Massal Data Staf"
      :message="`Yakin ingin menghapus ${selectedStaff.length} data staf ini secara permanen dari sistem?`"
      @confirm="executeBulkDelete"
      @cancel="cancelBulkDelete"
    />

    <!-- Notifikasi Toast -->
    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>
