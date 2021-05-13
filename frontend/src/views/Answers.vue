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
              :items="answersList"
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
                  v-b-modal.modal-answers-edit
                  @click="
                    answers_id = data.value;
                    editloadanswers();
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
                    answers_id = data.value;
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
        @show="resetanswers"
        @hidden="resetanswers"
        @ok="answersOk"
      >
        <form ref="form" @submit.stop.prevent="answersSubmit">
          <b-form-group
            :state="answersState"
            label="หมวดคำถาม : "
            label-for="answers-input"
            invalid-feedback="โปรดกรอกหมวดคำถาม"
          >
            <b-form-input
              id="answers-input"
              v-model="answers"
              :state="answersState"
              required
            />
          </b-form-group>
        </form>
      </b-modal>

      <!-- modal -->
      <b-modal
        id="modal-answers-edit"
        ref="my-modal"
        title="แก้ไขหมวดคำถาม"
        ok-title="บันทึก"
        cancel-title="ยกเลิก"
        cancel-variant="outline-secondary"
        @show="resetanswers"
        @hidden="resetanswers"
        @ok="answersedit"
      >
        <form ref="form" @submit.stop.prevent="editcategories">
          <b-form-group
            :state="answersState"
            label="หมวดคำถาม : "
            label-for="answers-input"
            invalid-feedback="โปรดกรอกหมวดคำถาม"
          >
            <b-form-input
              id="answers-input"
              v-model="edit_answers"
              :state="answersState"
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
    answers: "",
    answersState: null,
    answers_id: "",
    edit_answers: "",

    filter: null,
    filterOn: [],

    fields: [
      {
        key: "answers_data",
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
    answersList: [],
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
    this.loadanswers();
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    loadanswers() {
      this.$http
        .get("/api/answers/index")
        .then((response) => {
          this.answersList = response.data.data;
          console.log(this.answersList);
        })
        .catch((error) => {});
    },
    checkFormValidity() {
      const valid = this.$refs.form.checkValidity();
      this.answersState = valid;
      return valid;
    },
    resetanswers() {
      this.answers = "";
      this.answersState = null;
    },
    answersOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.answersSubmit();
    },
    answersedit(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.editcategories();
    },
    answersSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      }
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs["my-modal"].toggle("#toggle-btn");
      });

      this.$http
        .post("/api/answers/store", {
          answers: this.answers,
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
          this.resetanswers();
          this.loadanswers();
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
          this.resetanswers();
          this.loadanswers();
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
        .put(`/api/answers/update/${this.answers_id}`, {
          edit_answers: this.edit_answers,
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
          this.resetanswers();
          this.loadanswers();
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
          this.resetanswers();
          this.loadanswers();
        });
      console.log(this.answers_id);
    },
    editloadanswers() {
      this.$http
        .get(`/api/answers/show/${this.answers_id}`)
        .then((response) => {
          this.edit_answers = response.data.data.answers_data;
          console.log(this.edit_answers);
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
          .delete(`/api/answers/destroy/${this.answers_id}`)
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
              this.resetanswers();
              this.loadanswers();
            }
            this.resetanswers();
            this.loadanswers();
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