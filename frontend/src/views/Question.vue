 <template>
  <b-card title="คลังคำถาม">
    <div>
      <!-- button -->
      <b-button
        id="toggle-btn"
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
        v-b-modal.modal-question
        variant="outline-primary"
      >
        เพิ่มคำถาม
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
              </b-input-group>
            </b-form-group>
          </b-col>
          <br />
          <b-col md="12">
            <p>คลังคำถาม:</p>
          </b-col>
          <b-col cols="12">
            <b-table
              hover
              responsive
              :fields="fields"
              :items="questionsList"
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
                  v-b-modal.modal-question-edit
                  @click="
                    qid = data.value;
                    questionedit(data.value);
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
                    qid = data.value;
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
        id="modal-question"
        ref="my-modal"
        title="เพิ่มคำถาม"
        ok-title="บันทึก"
        cancel-title="ยกเลิก"
        cancel-variant="outline-secondary"
        @show="resetquestion"
        @hidden="resetquestion"
        @ok="questionOk"
      >
        <form ref="form" @submit.stop.prevent="questionSubmit">
          <b-row>
            <b-col cols="12">
              <h6>หมวดคำถาม</h6>

              <select class="form-control" v-model="c_id">
                <option value="">เลือก</option>
                <option
                  v-for="data in categoriesList"
                  :key="data.id"
                  :value="data.id"
                >
                  {{ data.categories_data }}
                </option>
              </select>
              <br />
            </b-col>
            <b-col cols="12">
              <h6>คำตอบ</h6>

              <select class="form-control" v-model="a_id">
                <option value="">เลือก</option>
                <option
                  v-for="data in answersList"
                  :key="data.id"
                  :value="data.id"
                >
                  {{ data.answers_data }}
                </option>
              </select>
              <br />
            </b-col>
            <b-col cols="12">
              <b-form-group
                :state="questionState"
                label="คำถาม : "
                label-for="question-input"
                invalid-feedback="โปรดกรอกคำถาม"
              >
                <b-form-input
                  id="question-input"
                  v-model="question"
                  :state="questionState"
                  required
                />
              </b-form-group>
            </b-col>
          </b-row>
        </form>
      </b-modal>

      <!-- modal -->
      <b-modal
        id="modal-question-edit"
        ref="my-modal"
        title="แก้ไขหมวดคำถาม"
        ok-title="บันทึก"
        cancel-title="ยกเลิก"
        cancel-variant="outline-secondary"
        @show="resetquestion"
        @hidden="resetquestion"
        @ok="questionedits"
      >
        <form ref="form" @submit.stop.prevent="questionSubmitedit">
          <b-row>
            <b-col cols="12">
              <h6>หมวดคำถาม</h6>

              <select class="form-control" v-model="c_id">
                <option value="">เลือก</option>
                <option
                  v-for="data in categoriesList"
                  :key="data.id"
                  :value="data.id"
                >
                  {{ data.categories_data }}
                </option>
              </select>
              <br />
            </b-col>
            <b-col cols="12">
              <h6>คำตอบ</h6>

              <select class="form-control" v-model="a_id">
                <option value="">เลือก</option>
                <option
                  v-for="data in answersList"
                  :key="data.id"
                  :value="data.id"
                >
                  {{ data.answers_data }}
                </option>
              </select>
              <br />
            </b-col>
            <b-col cols="12">
              <b-form-group
                :state="questionState"
                label="คำถาม : "
                label-for="question-input"
                invalid-feedback="โปรดกรอกคำถาม"
              >
                <b-form-input
                  id="question-input"
                  v-model="question"
                  :state="questionState"
                  required
                />
              </b-form-group>
            </b-col>
          </b-row>
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
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter((f) => f.sortable)
        .map((f) => ({ text: f.label, value: f.key }));
    },
  },
  data: () => ({
    questionsList: [],
    answersList: [],
    categoriesList: [],

    a_id: "",
    c_id: "",

    question: "",

    qid: "",

    filter: null,
    filterOn: [],

    fields: [
      {
        key: "questions_data",
        label: "หมวดหมู่",
        // formatter: (value) => `${value.first} ${value.last}`,
      },
      {
        key: "categories_data",
        label: "หมวดคำถาม",
        // formatter: (value) => `${value.first} ${value.last}`,
      },
      {
        key: "answers_data",
        label: "คำตอบ",
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
  }),
  mounted() {
    this.loadquestion();
    this.loadanswers();
    this.loadcategories();
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    loadquestion() {
      this.$http
        .get("/api/question/index")
        .then((response) => {
          this.questionsList = response.data.data;
        })
        .catch((error) => {});
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
    loadcategories() {
      this.$http
        .get("/api/category/index")
        .then((response) => {
          this.categoriesList = response.data.data;
        })
        .catch((error) => {});
    },

    checkFormValidity() {
      const valid = this.$refs.form.checkValidity();
      this.questionState = valid;
      return valid;
    },
    resetquestion() {
      this.question = "";
      this.questionState = null;
      this.a_id = "";
      this.c_id = "";
    },
    questionOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.questionSubmit();
    },
    questionSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      }

      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs["my-modal"].toggle("#toggle-btn");
      });

      this.$http
        .post("/api/question/store", {
          questions_data: this.question,
          a_id: this.a_id,
          c_id: this.c_id,
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
          this.loadquestion();
          this.loadanswers();
          this.loadcategories();
          this.resetquestion();
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
          this.loadquestion();
          this.loadanswers();
          this.loadcategories();
          this.resetquestion();
        });
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
        console.log(this.qid);

        this.$http
          .delete(`/api/question/destroy/${this.qid}`)
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
              console.log(this.qid);
              this.loadquestion();
              this.loadanswers();
              this.loadcategories();
              this.resetquestion();
            }
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
    questionedit(id) {
      this.$http
        .get(`/api/question/show/${id}`)
        .then((response) => {
          this.question = response.data.data.questions_data;
          this.a_id = response.data.data.a_id;
          this.c_id = response.data.data.c_id;

          console.log(this.response.data.data);
        })
        .catch((error) => {});
    },

    questionedits(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.questionSubmitedit();
    },
    questionSubmitedit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      }

      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs["my-modal"].toggle("#toggle-btn");
      });

      this.$http
        .put(`/api/question/update/${this.qid}`, {
          questions_data: this.question,
          a_id: this.a_id,
          c_id: this.c_id,
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
          this.loadquestion();
          this.loadanswers();
          this.loadcategories();
          this.resetquestion();
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
          this.loadquestion();
          this.loadanswers();
          this.loadcategories();
          this.resetquestion();
        });
      console.log(this.qid);
    },
  },
};
</script>