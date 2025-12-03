-- #*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*#*  		DC Login 		*#*#*#*#*#*#*##*#*#*#*#*#*#*#*#**#*#*#*#*#*#*#*#*#*#**##* 
-- Page23:
-- Pending Applications:
select e.EVENT_ID,
        e.EVENT_DATE,
        e.NAME_OF_ORGANIZATION,
        e.COMMITEE_REGISTER_NUMBER,
        e.NAME_OF_president,
        e.CONTACT_OF_PRESIDENT,
        e.MOBILE_OF_PRESIDENT,
        e.NAME_OF_VILLAGE,
        e.NAME_OF_TALUK,
        e.NAME_OF_DISTRICT,
	      e.PLACE_OF_EVENT,
        e.PINCODE,
    CASE 
        WHEN e.REQUEST_status = 'E' THEN 'Save'
        WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval'
        WHEN e.REQUEST_status = 'C' THEN 'Closed'
        WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection'
        WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector'
        WHEN e.REQUEST_status = 'N' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat'
        WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F1' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F3' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F4' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F5' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F6' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat'
        WHEN e.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'
        WHEN e.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'
        WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval'
        WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved'
        WHEN e.REQUEST_status = 'DN' THEN 'Need more information from District Collector'
        WHEN e.REQUEST_status = 'AN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'SN' THEN 'Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'N2' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N1' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N3' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'MN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report'
        WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS'
        WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event'
        WHEN e.REQUEST_status = 'R2' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R3' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R4' THEN 'Rejected'
    END AS REQUEST_status,
	     e.REQUEST_LETTER,
        e.ATTACH_MIMETYPE,
       e.ATTACH_FILENAME,
      e.ASSURANCE_BOND,
        e.ASSURANCE_BOND_ATTACH_MIMETYPE,
        e.ASSURANCE_BOND_ATTACH_FILENAME,
      e.INSURANCE_COPY,
        e.INSURANCE_COPY_ATTACH_MIMETYPE,
      e.INSURANCE_COPY_ATTACH_FILENAME,
      e.LAYOUT_SKETCH,
        e.LAYOUT_SKETCH_ATTACH_MIMETYPE,
      e.LAYOUT_SKETCH_ATTACH_FILENAME,
      e.OTHER_DOCS,
        e.OTHER_DOCS_ATTACH_MIMETYPE,
      e.OTHER_DOCS_ATTACH_FILENAME,
      e.PREVIOUS_EVENT,
        e.PREVIOUS_EVENT_ATTACH_MIMETYPE,
      e.PREVIOUS_EVENT_ATTACH_FILENAME,
      e.PANCHAYAT_UNION,
        e.PANCHAYAT_UNION_ATTACH_MIMETYPE,
      e.PANCHAYAT_UNION_ATTACH_FILENAME,
      e.FIR,
       e.FIR_ATTACH_MIMETYPE,
      e.FIR_ATTACH_FILENAME,
      e.UPLOAD_PHOTO,
        e.UPLOAD_PHOTO_ATTACH_MIMETYPE,
      e.UPLOAD_PHOTO_ATTACH_FILENAME,
    CASE
        WHEN TRIM(e.forward_to) = 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'
        WHEN TRIM(e.forward_to) = 'District Collector' THEN 'District Collector'
        WHEN TRIM(e.forward_to) = 'DAH & VS' THEN 'DAH & VS'
        WHEN TRIM(e.forward_to) = 'ACS & PS' THEN 'ACS & PS'
    END AS pending_with,
        e.mhs_COMMENTS,
        e. EVENT_TYPE,
        e.UPDATED_DATE,
        a.name,
      d.ARENA_UPLOAD,
        d.ARENA_UPLOAD_MIME_TYPE,
      d.ARENA_UPLOAD_FILENAME,
      d.LENGTH_ARENA_UPLOAD,
        d.LENGTH_ARENA_UPLOAD_MIME_TYPE,
      d.LENGTH_ARENA_UPLOAD_FILENAME,
      d.BULL_ARENA_UPLOAD,
        d.BULL_ARENA_UPLOAD_MIME_TYPE,
      d.BULL_ARENA_UPLOAD_FILENAME,
      d.BULL_EXAMINATION_UPLOAD,
        d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,
      d.BULL_EXAMINATION_UPLOAD_FILENAME
  from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d
  where a.role='District Collector'AND UPPER(e.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) and e.event_id=d.event_id
and e.request_status in ('A1','MN') and a.approver_id=:app_employee_id;


-- When Click on this report it redirect to another form page to view:
-- This page contain 1 form view and also TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T Report 
-- In this page having 5 Buttons (Back, Click to Add Commitee, 	Forward to MHS, Need more Information , Send SMS)
-- 2.When Click on Click to Add Commitee Button it move to another page:
-- THis page have 2 buttons (Back, Add_Commitee)

-- TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T Report
select  COMMITEE_ID,
       event_id,
       INSPECTION_DATE,
       COMMITEE_DEPARTMENT,
       COMMITEE_NAME,
       COMMITEE_DESIGNATION,
       COMMITEE_MAIL,
       COMMITEE_MOBILE,
       STATUS,
       CREATED_BY,
       CREATED_DATE,
       UPDATED_BY,
       UPDATED_DATE
  from TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T

-- When Click on Add_Commitee button Then RUn below query:

INSERT INTO TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T
	(  COMMITEE_ID,
	   event_id,
	   INSPECTION_DATE,
       COMMITEE_DEPARTMENT,
       COMMITEE_NAME,
       COMMITEE_DESIGNATION,
       COMMITEE_MAIL,
       COMMITEE_MOBILE)
VALUES(p_COMMITEE_ID,
	   p_event_id
	   p_INSPECTION_DATE,
       p_COMMITEE_DEPARTMENT,
       p_COMMITEE_NAME,
       p_COMMITEE_DESIGNATION,
       p_COMMITEE_MAIL,
       p_COMMITEE_MOBILE);	   

-- 3.When Click on Forward to MHS Run Below Query:

    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'D1',forward_to='Magesterial huzur sharishthadhar MHS ',DC_COMMENTS=:P101_DC_COMMENTS,
      UPDATED_BY=:APP_EMPLOYEE_ID
    WHERE
        EVENT_ID = :P101_EVENT_ID;


-- 4. When Click on Need more Information Then run Below query:

    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'DN',forward_to='Magesterial huzur sharishthadhar MHS',DC_COMMENTS=:P101_DC_COMMENTS
    WHERE
        EVENT_ID = :P101_EVENT_ID;

-- 5. When Click on Send SMS Button then , SMS Send to Commitee members for Inspection:
    var lv_content;
    var lv_template_id = '1007484033903037618';
   var lv_entity_id = '1001730754604494181';
var lv_api_key = 'r8o9j9JV';
var lv_sender_id = 'TNAHVS';
    var lv_otp_number = apex.item("P101_DATE").getValue();
    var lv_place = apex.item("P101_PLACE_OF_EVENT").getValue();
    var lv_district = apex.item("P101_NAME_OF_DISTRICT").getValue();
    var lv_mobile_numbers = apex.item("P101_COM_ID").getValue().split("|"); // Assuming P12_MOBILE is a string with mobile numbers separated by "|"
    var lv_name = lv_otp_number + '-' + lv_place;

    if (lv_mobile_numbers.length === 0 || lv_mobile_numbers[0] === '0') {
        apex.message.showErrors("Please Enter your Mobile Number.");
    } else {
        lv_content = 'Jallikattu /Manju virattu /vadamadu /erudu vidum vizha at ' + lv_place + '- joint inspection scheduled on '+ lv_otp_number + '-District Collector, district  ' + lv_district + '-TNAHVS';

        for (var i = 0; i < lv_mobile_numbers.length; i++) {
            var lv_mobile_number = '91' + lv_mobile_numbers[i];
            var lv_url = 'https://tmegov.onex-aura.com/api/sms?key=' + lv_api_key + '&to=' + lv_mobile_number + '&from=' + lv_sender_id + '&body=' + lv_content + '&entityid=' + lv_entity_id + '&templateid=' + lv_template_id;



-- Page26:
-- Need more information / Rejected:
select L.EVENT_ID,
      L.EVENT_DATE,
      L.NAME_OF_ORGANIZATION,
      L.COMMITEE_REGISTER_NUMBER,
      L.NAME_OF_president,
      L.CONTACT_OF_PRESIDENT,
      L.MOBILE_OF_PRESIDENT,
      L.NAME_OF_VILLAGE,
      L.NAME_OF_TALUK,
	  L.NAME_OF_DISTRICT,
	  L.PLACE_OF_EVENT,
    CASE 
        WHEN L.REQUEST_status = 'E' THEN 'Save'
        WHEN L.REQUEST_status = 'S' THEN 'Pending for Approval'
        WHEN L.REQUEST_status = 'C' THEN 'Closed'
        WHEN L.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection'
        WHEN L.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'
        WHEN L.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector'
        WHEN L.REQUEST_status = 'N' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat'
        WHEN L.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval'
        WHEN L.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F1' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F2' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F3' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F4' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F5' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'F6' THEN 'Waiting for DAH Approval'
        WHEN L.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat'
        WHEN L.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'
        WHEN L.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'
        WHEN L.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval'
        WHEN L.REQUEST_status = 'A4' THEN 'Principal Secretary Approved'
        WHEN L.REQUEST_status = 'DN' THEN 'Need more information from District Collector'
        WHEN L.REQUEST_status = 'AN' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'SN' THEN 'Resubmitted from Event Organizer'
        WHEN L.REQUEST_status = 'N2' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'N1' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'N3' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'MN' THEN 'Need more Information'
        WHEN L.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer'
        WHEN L.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report'
        WHEN L.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS'
        WHEN L.REQUEST_status = 'R11' THEN 'DC Not permitted the Event'
        WHEN L.REQUEST_status = 'R2' THEN 'Rejected'
        WHEN L.REQUEST_status = 'R3' THEN 'Rejected'
        WHEN L.REQUEST_status = 'R4' THEN 'Rejected'
    END AS REQUEST_status,
       L.PINCODE,
    l.REQUEST_LETTER,
     l.ATTACH_MIMETYPE,
       l.ATTACH_FILENAME,
    l.ASSURANCE_BOND,
      l.ASSURANCE_BOND_ATTACH_MIMETYPE,
       l.ASSURANCE_BOND_ATTACH_FILENAME,
   l.INSURANCE_COPY,
      l.INSURANCE_COPY_ATTACH_MIMETYPE,
       l.INSURANCE_COPY_ATTACH_FILENAME,
    l.LAYOUT_SKETCH,
     l.LAYOUT_SKETCH_ATTACH_MIMETYPE,
       l.LAYOUT_SKETCH_ATTACH_FILENAME,
    l.OTHER_DOCS,
     l.OTHER_DOCS_ATTACH_MIMETYPE,
       l.OTHER_DOCS_ATTACH_FILENAME,
    l.PREVIOUS_EVENT,
     l.PREVIOUS_EVENT_ATTACH_MIMETYPE,
       l.PREVIOUS_EVENT_ATTACH_FILENAME,
    l.PANCHAYAT_UNION,
      l.PANCHAYAT_UNION_ATTACH_MIMETYPE,
       l.PANCHAYAT_UNION_ATTACH_FILENAME,
    l.FIR,
      l.FIR_ATTACH_MIMETYPE,
      l.FIR_ATTACH_FILENAME,
   l.UPLOAD_PHOTO,
       l.UPLOAD_PHOTO_ATTACH_MIMETYPE,
       l.UPLOAD_PHOTO_ATTACH_FILENAME,
    CASE 
        WHEN TRIM(L.forward_to) = 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'
        WHEN TRIM(L.forward_to) = 'District Collector' THEN 'District Collector'
        WHEN TRIM(L.forward_to) = 'DAH & VS' THEN 'DAH & VS'
        WHEN TRIM(L.forward_to) = 'ACS & PS' THEN 'ACS & PS'
    END AS pending_with,
    d.ARENA_UPLOAD,
       d.ARENA_UPLOAD_MIME_TYPE,
       d.ARENA_UPLOAD_FILENAME,
    d.LENGTH_ARENA_UPLOAD,
       d.LENGTH_ARENA_UPLOAD_MIME_TYPE,
       d.LENGTH_ARENA_UPLOAD_FILENAME,
    d.BULL_ARENA_UPLOAD,
       d.BULL_ARENA_UPLOAD_MIME_TYPE,
       d.BULL_ARENA_UPLOAD_FILENAME,
    d.BULL_EXAMINATION_UPLOAD,
       d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,
       d.BULL_EXAMINATION_UPLOAD_FILENAME,
      L.MHS_COMMENTS,
      L. EVENT_TYPE,
       L.ASSISTANT_REMARKS,
	 L.DAH_VS_COMMENTS,
	 L.ACS_PS_COMMENTS,
     a.name,
	 A.DISTRICT,
	 L.PROCEED_COMMENTS
  from  TNEA_JALLIKATTU_EVENT_REGISTERATION_T L,
  TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D
  where a.role='District Collector'AND UPPER(L.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) AND L.EVENT_ID=D.EVENT_ID  
and L.request_status in ('AN','NM','NMM','R3')  and a.approver_id=:APP_EMPLOYEE_ID;

--Page27:
--When Click On Edit Button it move to another form page to view:
-- THis page have 2 Buttons (Back , Sent To Magesterial huzur sharishthadhar MHS)
-- When Click on Sent To Magesterial huzur sharishthadhar MHS Button run below query
BEGIN
    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'DNN',forward_to='Magesterial huzur sharishthadhar MHS',DC_COMMENTS=:P73_DC_COMMENTS
    WHERE
        EVENT_ID = :P73_EVENT_ID;
END;


-- Page28:
--District Monitoring Commitee Report
SELECT 
    d.MONITORING_ID,
    d.PARTICIPANTS,
    d.NUMBER_OF_BULLS,
    d.PREEVENT_ARRANGEMENTS,
    CASE 
        WHEN e.REQUEST_status = 'E' THEN 'Save'
        WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval'
        WHEN e.REQUEST_status = 'C' THEN 'Closed'
        WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection'
        WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector'
        WHEN e.REQUEST_status = 'N' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat'
        WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F1' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F3' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F4' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F5' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F6' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat'
        WHEN e.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'
        WHEN e.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'
        WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval'
        WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved'
        WHEN e.REQUEST_status = 'DN' THEN 'Need more information from District Collector'
        WHEN e.REQUEST_status = 'AN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'SN' THEN 'Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'N2' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N1' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N3' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'MN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report'
        WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS'
        WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event'
        WHEN e.REQUEST_status = 'R2' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R3' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R4' THEN 'Rejected'
    END AS REQUEST_status,
   d.ARENA_SIZE ,
     d.ARENA_LENGTH,
     d.ARENA_BREATH,
   d.ARENA_UPLOAD,
    d.ARENA_UPLOAD_MIME_TYPE,
    d.ARENA_UPLOAD_FILENAME,
     d.LENGTH_ARENA,
     d.LENGTH_ARENA_LENGTH,
     d.LENGTH_ARENA_BREATH,
   d.LENGTH_ARENA_UPLOAD,
    d.LENGTH_ARENA_UPLOAD_MIME_TYPE,
    d.LENGTH_ARENA_UPLOAD_FILENAME,
    d.BULL_RUN_AREA,
    d.BULL_ARENA_LENGTH,
    d.BULL_ARENA_BREATH,
   d.BULL_ARENA_UPLOAD,
   d.BULL_ARENA_UPLOAD_MIME_TYPE,
    d.BULL_ARENA_UPLOAD_FILENAME,
    d.BULL_EXAMINATION_AREA_SIZE,
    d.BULL_EXAMINATION_LENGTH,
     d.BULL_EXAMINATION_BREATH,
   d.BULL_EXAMINATION_UPLOAD,
   d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,
    d.BULL_EXAMINATION_UPLOAD_FILENAME,
    d.STATUS,
    d.CREATED_BY,
    d.CREATED_DATE,
    d.UPDATED_BY,
    d.UPDATED_DATE,
    d.EVENT_ID,
   d.JOIN_MONITORING_COMMITEE_REPORT,
    d.JOIN_ATTACH_MIMETYPE,
    d.JOIN_ATTACH_FILENAME
 FROM TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d
JOIN TNEA_JALLIKATTU_EVENT_REGISTERATION_T e ON e.EVENT_ID = d.EVENT_ID JOIN TNEA_JALLIKATTU_APPROVER_LOGIN_T a ON UPPER(e.NAME_OF_DISTRICT) = UPPER(a.DISTRICT)
WHERE e.request_status IN ('D2')  and D.JOIN_MONITORING_COMMITEE_REPORT is NOT null
AND A.APPROVER_ID = :APP_EMPLOYEE_ID;

--Page29:
-- When Click on edit button in this page it move to another form page to view:
-- This page contains 3 Buttons ( Back, Forward To Director of Animal Husbandry & Veterinary Services, Reject)

-- When Click on Forward To Director of Animal Husbandry & Veterinary Services Button Run Below query:
BEGIN
    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'A2',
		forward_to='Assistant',
		DC_COMMENTS=:P53_REMARKS_DC,
		updated_by=:app_employee_id
    WHERE
        EVENT_ID = :P53_EVENT_ID;
END;

-- When Click on Reject Button Run Below query:
BEGIN
    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'R1',
		forward_to='Magesterial huzur sharishthadhar MHS',
		DC_COMMENTS=:P53_REMARKS_DC,
		updated_by=:app_employee_id
    WHERE
        EVENT_ID = :P53_EVENT_ID;
END;



-- Page30:
--GO Issued:
select e.EVENT_ID,
       e.EVENT_DATE,
       e.NAME_OF_ORGANIZATION,
       e.COMMITEE_REGISTER_NUMBER,
       e.NAME_OF_president,
       e.CONTACT_OF_PRESIDENT,
       e.MOBILE_OF_PRESIDENT,
       e.NAME_OF_VILLAGE,
       e.NAME_OF_TALUK,
       e.NAME_OF_DISTRICT,
	   e.PLACE_OF_EVENT,
       e.PINCODE,
    CASE
        WHEN e.REQUEST_status = 'E' THEN 'Save'
        WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval'
        WHEN e.REQUEST_status = 'C' THEN 'Closed'
        WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection'
        WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector'
        WHEN e.REQUEST_status = 'N' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat'
        WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F1' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F3' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F4' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F5' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'F6' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat'
        WHEN e.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'
        WHEN e.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'
        WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval'
        WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved'
        WHEN e.REQUEST_status = 'DN' THEN 'Need more information from District Collector'
        WHEN e.REQUEST_status = 'AN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'SN' THEN 'Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'N2' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N1' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'N3' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'MN' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report'
        WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS'
        WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event'
        WHEN e.REQUEST_status = 'R2' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R3' THEN 'Rejected'
        WHEN e.REQUEST_status = 'R4' THEN 'Rejected'
    END AS REQUEST_status,
    e.REQUEST_LETTER,
        e.ATTACH_MIMETYPE,
        e.ATTACH_FILENAME,
    e.ASSURANCE_BOND,
        e.ASSURANCE_BOND_ATTACH_MIMETYPE,
        e.ASSURANCE_BOND_ATTACH_FILENAME,
    e.INSURANCE_COPY,
        e.INSURANCE_COPY_ATTACH_MIMETYPE,
        e.INSURANCE_COPY_ATTACH_FILENAME,
    e.LAYOUT_SKETCH,
        e.LAYOUT_SKETCH_ATTACH_MIMETYPE,
        e.LAYOUT_SKETCH_ATTACH_FILENAME,
    e.OTHER_DOCS,
        e.OTHER_DOCS_ATTACH_MIMETYPE,
        e.OTHER_DOCS_ATTACH_FILENAME,
    e.PREVIOUS_EVENT,
       e.PREVIOUS_EVENT_ATTACH_MIMETYPE,
        e.PREVIOUS_EVENT_ATTACH_FILENAME,
    e.PANCHAYAT_UNION,
        e.PANCHAYAT_UNION_ATTACH_MIMETYPE,
        e.PANCHAYAT_UNION_ATTACH_FILENAME,
    e.FIR,
        e.FIR_ATTACH_MIMETYPE,
        e.FIR_ATTACH_FILENAME,
    e.UPLOAD_PHOTO,
        e.UPLOAD_PHOTO_ATTACH_MIMETYPE,
        e.UPLOAD_PHOTO_ATTACH_FILENAME,
        e.UPDATED_DATE,
    CASE
        WHEN TRIM(e.forward_to) = 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'
        WHEN TRIM(e.forward_to) = 'District Collector' THEN 'District Collector'
        WHEN TRIM(e.forward_to) = 'DAH & VS' THEN 'DAH & VS'
        WHEN TRIM(e.forward_to) = 'ACS & PS' THEN 'ACS & PS'
    END AS pending_with,
        e.mhs_COMMENTS,
        e. EVENT_TYPE,
        a.name,
      e.PROCEEDINGS,
        e.PROCEED_ATTACH_MIMETYPE,
        e.PROCEED_ATTACH_FILENAME,
    p.PREEVENT,
        p.PREEVENT_ATTACH_MIMETYPE,
       p.PREEVENT_ATTACH_FILENAME,
        p.PREEVENT_COMMENTS,
    G.GO,
        G.GO_ATTACH_MIMETYPE,
        G.GO_ATTACH_FILENAME,
    D.JOIN_MONITORING_COMMITEE_REPORT,
         d.JOIN_ATTACH_MIMETYPE,
         d.JOIN_ATTACH_FILENAME
       
 from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d,TNEA_JALLIKATTU_EVENT_GO_T G,TNEA_JALLIKATTU_EVENT_PREEVENT_T p
  where TRIM(a.role)='District Collector'AND UPPER(e.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) AND E.EVENT_ID=D.EVENT_ID AND G.EVENT_ID=E.EVENT_ID and p.event_id=e.event_id
  and  e.request_status  in ('GO3')  
  AND A.APPROVER_ID=:app_employee_id 
  AND D.JOIN_MONITORING_COMMITEE_REPORT IS  NOT  NULL AND g.EVENT_date is not null ;
  
-- When Click on Edit  option it move to form view page:
-- This page contains 3 Buttons (Back , Permitted, Not Permitted)
-- (For EVENT_ID, EVENT_DATE, EVENT_TYPE, PLACE_OF_EVENT Values automatically pass from previous page)
INSERT INTO TNEA_JALLIKATTU_EVENT_PROCEED_T
	(  PROCEED_ID,
       EVENT_ID,
       EVENT_DATE,
       PROCEED,
       PROCEED_ATTACH_MIMETYPE,
       PROCEED_ATTACH_FILENAME,
       CREATED_BY,
       EVENT_TYPE,
       PROCEED_COMMENTS,
       PLACE_OF_EVENT)
VALUES(:p_PROCEED_ID,
       :p_EVENT_ID,
       :p_EVENT_DATE,
       :p_PROCEED,
       :p_PROCEED_ATTACH_MIMETYPE,
       :p_PROCEED_ATTACH_FILENAME,
       :p_CREATED_BY,
       :p_EVENT_TYPE,
       :p_PROCEED_COMMENTS,
       :p_PLACE_OF_EVENT);
--When click on Permitted Button, Running below query:	   
BEGIN
    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'C',PROCEED_COMMENTS=:P58_PROCEED_COMMENTS,UPDATED_BY=:APP_EMPLOYEE_ID
    WHERE
        EVENT_ID = :P58_EVENT_ID;
END;	   
  
--When click on Not-Permitted Button, Running below query:
BEGIN
    UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = 'R11',forward_to='District Collector',PROCEED_COMMENTS=:P58_PROCEED_COMMENTS
    WHERE
        EVENT_ID = :P58_EVENT_ID;
END;  
  
 
  
-- Page31:
--Proceedings Report
select PROCEED_ID,
       EVENT_ID,
       EVENT_DATE,
       PROCEED,
       PROCEED_ATTACH_MIMETYPE,
       PROCEED_ATTACH_FILENAME,
       EVENT_TYPE,
       PROCEED_COMMENTS,
       PLACE_OF_EVENT
  from TNEA_JALLIKATTU_EVENT_PROCEED_T

-- Page32:
-- All Application Status:( Same Like MHS Report) 	: Similar Page
select distinct 
        e.EVENT_ID,
        e.EVENT_DATE,
        e.NAME_OF_ORGANIZATION,
        e.COMMITEE_REGISTER_NUMBER,
        e.NAME_OF_president,
        e.CONTACT_OF_PRESIDENT,
        e.MOBILE_OF_PRESIDENT,
        e.NAME_OF_VILLAGE,
        e.NAME_OF_TALUK,
        e.NAME_OF_DISTRICT,
	    e.PLACE_OF_EVENT,
    CASE 
        WHEN e.REQUEST_status = 'E' THEN 'Save'
        WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval'
        WHEN e.REQUEST_status = 'C' THEN 'Closed'
        WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection'
        WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector'
        WHEN e.REQUEST_status = 'N' THEN 'Need more Information'
        WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat'
        WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval'
        WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status IN ('F1', 'F2', 'F3', 'F4', 'F5', 'F6') THEN 'Waiting for DAH Approval'
        WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat'
        WHEN e.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'
        WHEN e.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'
        WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval'
        WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved'
        WHEN e.REQUEST_status = 'DN' THEN 'Need more information from District Collector'
        WHEN e.REQUEST_status IN ('AN', 'SN', 'N2', 'N1', 'N3', 'MN') THEN 'Need more Information'
        WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer'
        WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report'
        WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS'
        WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event'
        WHEN e.REQUEST_status IN ('R2', 'R3', 'R4') THEN 'Rejected'
    END AS REQUEST_status,
       e.REQUEST_LETTER,
        e.ATTACH_MIMETYPE,
        e.ATTACH_FILENAME,
       e.ASSURANCE_BOND,
        e.ASSURANCE_BOND_ATTACH_MIMETYPE,
        e.ASSURANCE_BOND_ATTACH_FILENAME,
       e.INSURANCE_COPY,
        e.INSURANCE_COPY_ATTACH_MIMETYPE,
       e.INSURANCE_COPY_ATTACH_FILENAME,
       e.LAYOUT_SKETCH,
        e.LAYOUT_SKETCH_ATTACH_MIMETYPE,
       e.LAYOUT_SKETCH_ATTACH_FILENAME,
       e.OTHER_DOCS,
        e.OTHER_DOCS_ATTACH_MIMETYPE,
       e.OTHER_DOCS_ATTACH_FILENAME,
       e.PREVIOUS_EVENT,
        e.PREVIOUS_EVENT_ATTACH_MIMETYPE,
        e.PREVIOUS_EVENT_ATTACH_FILENAME,
       e.PANCHAYAT_UNION,
        e.PANCHAYAT_UNION_ATTACH_MIMETYPE,
        e.PANCHAYAT_UNION_ATTACH_FILENAME,
       e.FIR,
        e.FIR_ATTACH_MIMETYPE,
       e.FIR_ATTACH_FILENAME,
       e.UPLOAD_PHOTO,
        e.UPLOAD_PHOTO_ATTACH_MIMETYPE,
        e.UPLOAD_PHOTO_ATTACH_FILENAME,
    CASE TRIM(e.forward_to)
        WHEN 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'
        WHEN 'District Collector' THEN 'District Collector'
        WHEN 'DAH & VS' THEN 'DAH & VS'
        WHEN 'ACS & PS' THEN 'ACS & PS'
    END AS pending_with,
        e.mhs_COMMENTS,
        e. EVENT_TYPE,
        e.MANAGER_REMARKS,
        e.JSECTION_REMARKS,
        e.ASSISTANT_DIRECTOR_REMARKS,
        e.JOINT_REMARKS,
        e.ADDITIONAL_DIRECTOR_REMARKS,
       d.ARENA_UPLOAD,
        d.ARENA_UPLOAD_MIME_TYPE,
        d.ARENA_UPLOAD_FILENAME,
       d.LENGTH_ARENA_UPLOAD,
        d.LENGTH_ARENA_UPLOAD_MIME_TYPE,
       d.LENGTH_ARENA_UPLOAD_FILENAME,
       d.BULL_ARENA_UPLOAD,
        d.BULL_ARENA_UPLOAD_MIME_TYPE,
        d.BULL_ARENA_UPLOAD_FILENAME,
       d.BULL_EXAMINATION_UPLOAD,
        d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,
       d.BULL_EXAMINATION_UPLOAD_FILENAME,
          e.DC_COMMENTS,
        e.DAH_VS_COMMENTS,
        e.ACS_PS_COMMENTS,
        D.JOINT_REPORT_COMMENTS,
        e.ASSISTANT_REMARKS,
       d.JOIN_MONITORING_COMMITEE_REPORT,
        d.JOIN_ATTACH_MIMETYPE,
       d.JOIN_ATTACH_FILENAME,
        e.UPDATED_DATE,
        (SELECT PROCEED_COMMENTS FROM TNEA_JALLIKATTU_EVENT_PROCEED_T WHERE EVENT_ID = e.EVENT_ID),
        (SELECT PREEVENT_COMMENTS FROM TNEA_JALLIKATTU_EVENT_PREEVENT_T WHERE EVENT_ID = e.EVENT_ID)
   from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D,TNEA_JALLIKATTU_APPROVER_LOGIN_T a
   where  E.EVENT_ID=D.EVENT_ID and UPPER(e.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) and  A.APPROVER_ID=:app_employee_id
      ORDER BY e.UPDATED_DATE DESC;;
	  

	  
-- All Reports - 1.Post event Inspection Report 	2.	Registered bulls report 	3. Registered Participants report
--Post event Inspection Report:( Same Like MHS Report) 	: Similar Page

select  P.S_NUMBER,
        P.DISTRICT_WISE_S_NO,
        P.EVENT_ID,
        P.DISTRICT,
        P.TALUK,
        P.EVENT_PLACE,
        P.GO_NUMBER,
        P.GO_DATE,
        P.PERMITTED_DATE,
        P.ACTUAL_DATE_OF_CONDUCT_EVENT,
        P. TYPE_OF_EVENT,
        P. NO_OF_BULLS_REGISTERED,
        P.NO_OF_BULLS_REJECTED,
        P.REASON_FOR_REJECTION,
        P. NO_OF_BULLS_PARTICIPATED,
        P.NO_OF_BULLS_INJURED,
        P.NO_OF_BULLS_DIED,
        P.NO_OF_PARTICIPANTS_REGISTERED,
        P. NO_OF_PARTICIPANTS_REJECTED,
        P.NO_OF_PARTICIPANTS_PERMITTED,
        P.NO_OF_PARTICIPANTS_INJURED_MAJOR,
        P. NO_OF_PARTICIPANTS_INJURED_MINOR,
        P. NO_OF_PARTICIPANTS_DIED,
        P.SPECTATORS_OWNERS_INJURED_MAJOR,
        P.SPECTATORS_OWNERS_INJURED_MINOR,
        P. SPECTATORS_OWNER_DIED,
        P. NUMBER_OF_POLICE_PERSONNEL_INJURED,
        P. NUMBER_OF_POLICE_DIED,
        P.FIR_No,
        P. FIR_REMARKS,
       P.FIR_ATTACHMENT,
        P.FIR_ATTACH_MIMETYPE,
        P. FIR_ATTACH_FILENAME,
       P.DEATH_REPORT_ATTACHMENT,
        P.DEATH_REPORT_ATTACH_MIMETYPE,
        P.DEATH_REPORT_ATTACH_FILENAME,
        P.STATUS,
        P. CREATED_BY,
        P. CREATED_DATE,
        P.UPDATED_BY,
        P. UPDATED_DATE
  from TNEA_JALLIKATTU_POST_EVENT_T P,TNEA_JALLIKATTU_APPROVER_LOGIN_T A WHERE P.DISTRICT=A.DISTRICT AND 
  A.APPROVER_ID=:APP_EMPLOYEE_ID;
  
  
  
--2.Registered Bull Reports
select b.BULLS_ID, 
	b.EVENT_TYPE, 
	b.EVENT_DATE, 
	b.PLACE_OF_EVENT, 
	b.DISTRICT, 
	 b.STATUS, 
	 b.CREATED_BY, 
	 b.CREATED_DATE, 
	 b.UPDATED_DATE, 
	 b.UPDATED_BY, 
	b.SELECTED_BULL, 
	b.MOBILE
  from TNEA_JALLIKATTU_BULL_EVENT_T b,TNEA_JALLIKATTU_APPROVER_LOGIN_T a
  where a.district = b.DISTRICT and a.approver_id=:app_employee_id;
  
-- Page35:
--3.Registered Participants Reports
select 
    p.PART_ID, 
	 p.EVENT_TYPE, 
	 p.EVENT_DATE, 
	 p.PLACE_OF_EVENT, 
	 p.DISTRICT, 
	 p.SELECT_PART, 
	 p.MOBILE_NUMBER, 
	 p.STATUS, 
	 p.CREATED_BY, 
	 p.CREATED_DATE, 
	 p.UPDATED_DATE, 
	 p.UPDATED_BY
  from TNEA_JALLIKATTU_PART_EVENT_T p,TNEA_JALLIKATTU_APPROVER_LOGIN_T a
  where a.district = p.DISTRICT and a.approver_id=:app_employee_id;
