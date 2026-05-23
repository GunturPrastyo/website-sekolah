<script setup>
import { ref, computed, onMounted } from "vue";
import api from "@/api/index.js";
import {
  PhPlusCircle,
  PhPencilSimple,
  PhTrash,
  PhXCircle,
  PhFloppyDisk,
  PhMagnifyingGlass,
  PhUser,
  PhShieldCheck,
  PhEnvelopeSimple,
  PhX,
  PhGoogleLogo,
} from "@phosphor-icons/vue";
import ConfirmModal from "@/components/admin/ConfirmModal.vue";
import ToastNotification from "@/components/admin/ToastNotification.vue";

const roles = [
  { id: "super_admin", name: "Super Admin" },
  { id: "admin", name: "Admin" },
];

const usersList = ref([]);

const form = ref({
  id: null,
  name: "",
  email: "",
  role: "admin",
  provider: "local",
});

const isFormVisible = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const showToast = ref(false);
const toastData = ref({ title: "", message: "", type: "success" });
const searchQuery = ref("");
const filterRole = ref("semua");

const triggerToast = (title, message, type = "success") => {
  toastData.value = { title, message, type };
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const fetchUsers = async () => {
  try {
    const response = await api.get("/api/users");
    usersList.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data pengguna:", error);
    triggerToast("Error", "Gagal memuat data pengguna.", "error");
  }
};

onMounted(() => {
  fetchUsers();
});

const resetForm = () => {
  form.value = {
    id: null,
    name: "",
    email: "",
    role: "admin",
    provider: "local",
  };
  isEditing.value = false;
};

const hideForm = () => {
  resetForm();
  isFormVisible.value = false;
  document.body.style.overflow = "";
};

const showAddForm = () => {
  resetForm();
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const addEntry = async () => {
  if (!form.value.name || !form.value.email) {
    triggerToast("Gagal Menyimpan", "Nama dan Email wajib diisi!", "error");
    return;
  }

  try {
    await api.post("/api/users", form.value);
    await fetchUsers(); // Muat ulang tabel
    hideForm();
    triggerToast(
      "Berhasil Ditambahkan",
      form.value.provider === "google"
        ? "Pengguna akun Google berhasil didaftarkan."
        : "Pengguna baru ditambahkan dengan sandi: password123"
    );
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menyimpan",
      error.response?.data?.message || "Terjadi kesalahan pada server.",
      "error"
    );
  }
};

const startEdit = (item) => {
  isEditing.value = true;
  form.value = {
    id: item.id,
    name: item.name,
    email: item.email,
    role: item.role,
    provider: item.provider || "local",
  };
  isFormVisible.value = true;
  document.body.style.overflow = "hidden";
};

const saveEntry = async () => {
  if (!form.value.name || !form.value.email) {
    triggerToast("Gagal Menyimpan", "Nama dan Email wajib diisi!", "error");
    return;
  }

  try {
    await api.put(`/api/users/${form.value.id}`, form.value);
    await fetchUsers();
    hideForm();
    triggerToast("Perubahan Disimpan", "Data pengguna berhasil diperbarui.");
  } catch (error) {
    console.error(error);
    triggerToast(
      "Gagal Menyimpan",
      error.response?.data?.message || "Terjadi kesalahan pada server.",
      "error"
    );
  }
};

const deleteEntry = (id) => {
  itemToDelete.value = id;
  isDeleteModalOpen.value = true;
};

const confirmDelete = async () => {
  if (itemToDelete.value !== null) {
    try {
      await api.delete(`/api/users/${itemToDelete.value}`);
      await fetchUsers();
      itemToDelete.value = null;
      triggerToast("Data Dihapus", "Pengguna berhasil dihapus dari sistem.", "info");
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

const filteredUsers = computed(() => {
  let result = usersList.value;

  if (filterRole.value !== "semua") {
    result = result.filter((item) => item.role === filterRole.value);
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(
      (item) =>
        item.name.toLowerCase().includes(query) ||
        item.email.toLowerCase().includes(query)
    );
  }
  return result;
});

const getRoleName = (id) => {
  const role = roles.find((r) => r.id === id);
  return role ? role.name : id;
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
          Manajemen Pengguna
        </h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
          Kelola hak akses dan akun staf admin website.
        </p>
      </div>
      <button
        @click="showAddForm"
        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
      >
        <PhPlusCircle class="w-5 h-5 mr-2" />
        Tambah Pengguna
      </button>
    </div>

    <!-- Form Tambah/Edit -->
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
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6"
        @click="hideForm"
      >
        <div
          class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
          @click.stop
        >
          <!-- Modal Header -->
          <div
            class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50"
          >
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">
              {{ isEditing ? "Edit Data Pengguna" : "Tambah Pengguna Baru" }}
            </h3>
            <button
              @click="hideForm"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
              <PhX class="w-6 h-6" />
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="userForm" @submit.prevent="isEditing ? saveEntry() : addEntry()">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Nama Lengkap
                  </label>
                  <input
                    type="text"
                    v-model="form.name"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan nama pengguna"
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Alamat Email
                  </label>
                  <input
                    type="email"
                    v-model="form.email"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                    placeholder="admin@sekolah.com"
                  />
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Peran (Role) Akses</label
                  >
                  <select
                    v-model="form.role"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option v-for="role in roles" :key="role.id" :value="role.id">
                      {{ role.name }}
                    </option>
                  </select>
                </div>

                <div class="md:col-span-1">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >Metode Login</label
                  >
                  <select
                    v-model="form.provider"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="local">Email & Kata Sandi</option>
                    <option value="google">Akun Google (SSO)</option>
                  </select>
                </div>

                <div class="md:col-span-1" v-if="form.provider === 'local'">
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Kata Sandi
                  </label>
                  <input
                    type="text"
                    value="password123"
                    disabled
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                  />
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    * Kata sandi diatur otomatis menjadi
                    <strong class="text-gray-700 dark:text-gray-300">password123</strong>.
                  </p>
                </div>

                <div class="md:col-span-1" v-else>
                  <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                  >
                    Kata Sandi
                  </label>
                  <div
                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-blue-50 dark:bg-slate-800/50 text-blue-700 dark:text-blue-400 flex items-center cursor-not-allowed"
                  >
                    <PhGoogleLogo class="w-5 h-5 mr-2" />
                    <span class="font-medium text-sm">Login via Google Workspace</span>
                  </div>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    * Pengguna akan masuk melalui akun Google miliknya. Tidak memerlukan
                    kata sandi.
                  </p>
                </div>
              </div>
            </form>
          </div>

          <!-- Modal Footer -->
          <div
            class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-700/50 flex justify-end gap-3"
          >
            <button
              type="button"
              @click="hideForm"
              class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhXCircle class="w-5 h-5 mr-2" /> Batal
            </button>
            <button
              form="userForm"
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <PhFloppyDisk v-if="isEditing" class="w-5 h-5 mr-2" />
              <PhPlusCircle v-else class="w-5 h-5 mr-2" />
              {{ isEditing ? "Simpan Perubahan" : "Simpan Pengguna" }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Data Table -->
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
    >
      <div
        class="p-6 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
      >
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
          <div class="relative w-full md:w-80">
            <div
              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
              <PhMagnifyingGlass class="w-5 h-5 text-gray-400" />
            </div>
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Cari nama atau email..."
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
          <select
            v-model="filterRole"
            class="block w-full md:w-48 px-3 py-2.5 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm cursor-pointer"
          >
            <option value="semua">Semua Role</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr
              class="bg-gray-50 dark:bg-slate-700/50 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
            >
              <th class="px-6 py-4">Nama Pengguna</th>
              <th class="px-6 py-4 hidden sm:table-cell">Peran (Role)</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            <tr v-if="filteredUsers.length === 0">
              <td
                colspan="3"
                class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
              >
                <PhUser class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-500 mb-3" />
                <p>Tidak ada pengguna yang ditemukan.</p>
              </td>
            </tr>
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group"
            >
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0"
                  >
                    <PhUser class="w-5 h-5" weight="fill" />
                  </div>
                  <div>
                    <span
                      class="block text-sm font-semibold text-gray-800 dark:text-gray-200"
                      >{{ user.name }}</span
                    >
                    <div class="flex items-center gap-2 mt-0.5">
                      <span
                        class="text-xs text-gray-400 dark:text-gray-500 flex items-center"
                      >
                        <PhEnvelopeSimple class="w-3.5 h-3.5 mr-1" />
                        {{ user.email }}
                      </span>
                      <span
                        v-if="user.provider === 'google'"
                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400"
                        title="Login menggunakan Akun Google"
                      >
                        <PhGoogleLogo class="w-3 h-3 mr-1" /> Google
                      </span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 hidden sm:table-cell">
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                  :class="
                    user.role === 'super_admin'
                      ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
                      : 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300'
                  "
                >
                  <PhShieldCheck
                    v-if="user.role === 'super_admin'"
                    class="w-3.5 h-3.5 mr-1"
                  />
                  {{ getRoleName(user.role) }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div
                  class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <button
                    @click="startEdit(user)"
                    class="p-1.5 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-md transition-colors"
                    title="Edit Pengguna"
                  >
                    <PhPencilSimple class="w-4 h-4" />
                  </button>
                  <button
                    @click="deleteEntry(user.id)"
                    class="p-1.5 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/50 rounded-md transition-colors"
                    title="Hapus Pengguna"
                  >
                    <PhTrash class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ConfirmModal
      :isOpen="isDeleteModalOpen"
      title="Hapus Pengguna"
      message="Yakin ingin menghapus pengguna ini? Akses loginnya akan dicabut secara permanen."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
    <ToastNotification
      :isOpen="showToast"
      :title="toastData.title"
      :message="toastData.message"
      :type="toastData.type"
      @close="showToast = false"
    />
  </main>
</template>

<style scoped>
.transition-all {
  overflow: hidden;
}
</style>
