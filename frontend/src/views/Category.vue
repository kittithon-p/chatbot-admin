 <template>
  <b-card title="คลังหมวดคำถาม">
    <div>
      <!-- button -->
      <b-button
        id="toggle-btn"
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
        v-b-modal.modal-prevent-closing
        variant="outline-primary"
      >
        เพิ่มหมวดคำถาม
      </b-button>

      <!-- modal data -->
      <div class="mt-2">
        <b-row>
          <b-col md="12">
            <b-form-group
              label-align-sm="right"
              label-size="sm"
              label-for="filterInput"
              class="mb-0"
            >
              <b-input-group size="sm">
                <b-form-input
                  id="filterInput"
                  v-model="filter"
                  type="search"
                  placeholder="กรอกหมวดหมู่..."
                />
                <!-- <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''">
                    ล้าง
                  </b-button>
                </b-input-group-append> -->
              </b-input-group>
            </b-form-group>
          </b-col>
          <br />
          <b-col md="12">
            <p>คลังหมวดคำถาม:</p>
          </b-col>
          <b-col cols="12">
            <b-table
              hover
              responsive
              :fields="fields"
              :items="CategoryList"
              :filter="filter"
              :filter-included-fields="filterOn"
              @filtered="onFiltered"
            >
              <template #cell(categories)="data">
                <b-badge variant="light-primary">
                  {{ data.value }}
                </b-badge>
              </template>

              <template #cell(id)="data">
                <b-button
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-warning"
                  size="sm"
                  v-b-modal.modal-category-edit
                  @click="
                    category_id = data.value;
                    editloadcategory();
                  "
                >
                  <feather-icon icon="Edit3Icon" class="mr-50" />
                  <span class="align-middle">แก้ไข</span>
                </b-button>

                <b-button
                  v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                  variant="outline-danger"
                  size="sm"
                  @click="
                    category_id = data.value;
                    confirmDestroy();
                  "
                >
                  <feather-icon icon="XCircleIcon" class="mr-50" />
                  <span class="align-middle">ลบ</span>
                </b-button>
              </template>
            </b-table>
          </b-col>
        </b-row>
      </div>

      <!-- modal -->
      <b-modal
        id="modal-prevent-closing"
        ref="my-modal"
        title="เพิ่มหมวดคำถาม"
        ok-title="บันทึก"
        cancel-title="ยกเลิก"
        cancel-variant="outline-secondary"
        @show="resetCategory"
        @hidden="resetCategory"
        @ok="CategoryOk"
      >
        <form ref="form" @submit.stop.prevent="CategorySubmit">
          <b-form-group
            :state="CategoryState"
            label="หมวดคำถาม : "
            label-for="category-input"
            invalid-feedback="โปรดกรอกหมวดคำถาม"
          >
            <b-form-input
              id="category-input"
              v-model="category"
              :state="CategoryState"
              required
            />
          </b-form-group>
        </form>
      </b-modal>

      <!-- modal -->
      <b-modal
        id="modal-category-edit"
        ref="my-modal"
        title="แก้ไขหมวดคำถาม"
        ok-title="บันทึก"
        cancel-title="ยกเลิก"
        cancel-variant="outline-secondary"
        @show="resetCategory"
        @hidden="resetCategory"
        @ok="Categoryedit"
      >
        <form ref="form" @submit.stop.prevent="editcategories">
          <b-form-group
            :state="CategoryState"
            label="หมวดคำถาม : "
            label-for="category-input"
            invalid-feedback="โปรดกรอกหมวดคำถาม"
          >
            <b-form-input
              id="category-input"
              v-model="edit_categories"
              :state="CategoryState"
              required
            />
          </b-form-group>
        </form>
      </b-modal>
    </div>
  </b-card>
</template>

<script>
import {
  BButton,
  BFormGroup,
  BFormInput,
  BModal,
  VBModal,
  BListGroup,
  BListGroupItem,
  BCard,
  BCardText,
  BLink,
  BTable,
  BBadge,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BButton,
    BFormGroup,
    BFormInput,
    BListGroup,
    BListGroupItem,
    BModal,
    BCard,
    BCardText,
    BLink,
    BTable,

    BBadge,
    ToastificationContent,
  },
  directives: {
    "b-modal": VBModal,
    Ripple,
  },
  data: () => ({
    category: "",
    CategoryState: null,
    category_id: "",
    edit_categories: "",

    filter: null,
    filterOn: [],

    fields: [
      {
        key: "categories_data",
        label: "หมวดหมู่",
        // formatter: (value) => `${value.first} ${value.last}`,
      },
      {
        key: "updated_at",
        label: "แก้ไขข้อมูลล่าสุด",
        // formatter: (value) => `${value.first} ${value.last}`,
      },
      {
        key: "id",
        label: "ตัวดำเนินการ",
        // formatter: (value) => `${value.first} ${value.last}`,
      },
    ],
    CategoryList: [],
  }),
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter((f) => f.sortable)
        .map((f) => ({ text: f.label, value: f.key }));
    },
  },
  mounted() {
    this.loadcategory();
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    loadcategory() {
      this.$http
        .get("/api/category/index")
        .then((response) => {
          this.CategoryList = response.data.data;
        })
        .catch((error) => {});
    },
    checkFormValidity() {
      const valid = this.$refs.form.checkValidity();
      this.CategoryState = valid;
      return valid;
    },
    resetCategory() {
      this.category = "";
      this.CategoryState = null;
    },
    CategoryOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.CategorySubmit();
    },
    Categoryedit(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.editcategories();
    },
    CategorySubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      }
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs["my-modal"].toggle("#toggle-btn");
      });

      this.$http
        .post("/api/category/store", {
          categories: this.category,
        })
        .then(() => {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "ดำเนินการสำเร็จ",
              icon: "CheckIcon",
              text: `เพิ่มข้อมูลเข้าคลังหมวดคำถามสำเร็จ`,
              variant: "success",
            },
          });
          this.resetCategory();
          this.loadcategory();
        })
        .catch((error) => {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "เกิดข้อผิดพลาดในการดำเนินการ",
              icon: "InfoIcon",
              text: "กรุณาตรวจสอบความถูกต้องแล้วดำเนินการใหม่อีกครั้ง",
              variant: "danger",
            },
          });
          this.resetCategory();
          this.loadcategory();
        });
    },
    editcategories() {
      if (!this.checkFormValidity()) {
        return;
      }
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs["my-modal"].toggle("#toggle-btn");
      });
      this.$http
        .put(`/api/category/update/${this.category_id}`, {
          edit_categories: this.edit_categories,
        })
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "ดำเนินการสำเร็จ",
              icon: "CheckIcon",
              text: `แก้ไขข้อมูลเข้าคลังหมวดคำถามสำเร็จ`,
              variant: "success",
            },
          });
          this.resetCategory();
          this.loadcategory();
        })
        .catch((error) => {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "เกิดข้อผิดพลาดในการดำเนินการ",
              icon: "InfoIcon",
              text: "กรุณาตรวจสอบความถูกต้องแล้วดำเนินการใหม่อีกครั้ง",
              variant: "danger",
            },
          });
          this.resetCategory();
          this.loadcategory();
        });
      console.log(this.category_id);
    },
    editloadcategory() {
      this.$http
        .get(`/api/category/show/${this.category_id}`)
        .then((response) => {
          this.edit_categories = response.data.data.categories_data;
          console.log(this.edit_categories);
        })
        .catch((error) => {});
    },
    confirmDestroy() {
      this.$swal({
        title: "คุณต้องการลบ",
        text: "ข้อมูลจริงหรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
      }).then((result) => {
        this.$http
          .delete(`/api/category/destroy/${this.category_id}`)
          .then((response) => {
            if (result.value) {
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: "ดำเนินการสำเร็จ",
                  icon: "CheckIcon",
                  text: `ลบข้อมูลคลังหมวดคำถามสำเร็จ`,
                  variant: "success",
                },
              });
              this.resetCategory();
              this.loadcategory();
            }
            this.resetCategory();
            this.loadcategory();
          })
          .catch((error) => {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: "เกิดข้อผิดพลาดในการดำเนินการ",
                icon: "InfoIcon",
                text: "กรุณาตรวจสอบความถูกต้องแล้วดำเนินการใหม่อีกครั้ง",
                variant: "danger",
              },
            });
          });
      });
    },
  },
};
</script>