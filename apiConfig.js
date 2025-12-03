
const environment = "dev";

const baseUrls = {
    dev: "https://jallikattu.tn.gov.in/jallikattu_api/v1",
    prod: "https://tngis.tnega.org/lcap_api/jallikattu/v1"
};

const BASE_API_URL = baseUrls[environment];

const apiPaths = {
    login: "/access/login",
    logout: "/access/logout",
    forgotPassword: "/access/forgot_password",
    signupOtp: "/access/signup_otp",
    signup: "/access/signup",

    //mhs_user
    getMhsUser: "/mhs_user/get_mhs_user",
    addMember: "/mhs_user/mhs_add_member",
    allApplicationStatus: "/mhs_user/all_application_status",
    fetchEditCategory: "mhs_user/fetch_edit_category",
    mhsReject: "/mhs_user/mhs_reject",
    mhsApproved: "/mhs_user/mhs_approved",
    mhsApprovedBullReport: "/mhs_user/mhs_approved_bull_report",
    mhsApprovedParticipantReport: "/mhs_user/mhs_approved_participant_report",
    forApproveBullParticipant: "/mhs_user/for_approve_bull_participant",
    approveBullParticipant: "/mhs_user/approve_bull_participant",
    getDistrictMonitoring: "/mhs_user/get_district_monitoring",
    updateDistrictMonitoring: "/mhs_user/mhs_district_monitor_update",
    getDistrict: "/mhs_user/get_district",
    eventType: "/registration/event_type",
    getEventId: "/mhs_user/get_event_id",
    getEventPlace: "/mhs_user/get_event_place",
    getTaluk: "/mhs_user/get_taluk",
    mhsFinalEventCompletionForm: "/mhs_user/mhs_final_event_completion_form",

    getDistrictMonitoring: "/mhs_user/get_district_monitoring",
    approveParticipant: "/mhs_user/approve_participant",
    fetchEditCategory: "/mhs_user/fetch_edit_category",
    districtMonitorEdit: "/mhs_user/mhs_district_monitor_edit.php",
    districtMonitorUpdate: "/mhs_user/mhs_district_monitor_update"
};


function getApiUrl(pathKey) {
    if (!apiPaths[pathKey]) {
        console.error(`API path "${pathKey}" not found!`);
        return null;
    }
    return BASE_API_URL + apiPaths[pathKey];
}
