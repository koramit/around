import{k as f,x as w,r as D,h as m,c as u,b as d,d as r,f as q,g as y,e as k,m as z,y as F,p as C,u as A,F as L}from"./app-Cw0y1MbD.js";import{_ as V}from"./FormCheckbox-DkKIstu2.js";import{_ as n}from"./FormInput-qCU0J5nu.js";import{_ as P}from"./FormTextarea-CCgoRvhJ.js";import{_ as i}from"./FormSelect-Dp1cW_Gc.js";import{_ as b}from"./FormRadio-l_87-R30.js";import S from"./FormSelectOther-BPmPIfFq.js";import{u as h}from"./useSelectOther-CICpfGd9.js";import{I as G}from"./IconCopy-BEpI3zY-.js";import"./ModalDialog-Muogfn8j.js";const j={class:"mt-6 md:mt-12 xl:mt-24 flex justify-between items-center",id:"prescription"},B={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},E={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},R={class:"grid md:grid-cols-2 gap-2 xl:gap-8 my-2 md:my-4 xl:mt-8"},T={key:0,class:"grid gap-2 md:gap-4 xl:gap-8 mt-2 md:mt-4 xl:mt-8"},H={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},W={key:0,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},J={key:1},K={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},M={key:2,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},Q={key:3,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},X={key:4,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},Y={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},Z={class:"grid gap-2 md:grid-cols-2"},N={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},me={__name:"SLEDDForm",props:{modelValue:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},emits:["update:modelValue","autosave","copyPreviousOrder"],setup(v,{emit:I}){const x=v,$=I,l=f({...x.modelValue}),_={anticoagulant:l.anticoagulant??null,access_type:l.access_type??null};w(()=>l,a=>{a.anticoagulant!==_.anticoagulant&&(a.anticoagulant_none_drip_via_peripheral_iv=!1,a.anticoagulant_none_nss_200ml_flush_q_hour=!1,a.heparin_loading_dose=null,a.heparin_maintenance_dose=null,a.enoxaparin_dose=null,a.fondaparinux_bolus_dose=null,a.tinzaparin_dose=null,a.anticoagulant_other=null,_.anticoagulant=a.anticoagulant),(a.access_type==="pending"||a.access_type==="Remove"||["DLC","Perm cath"].includes(a.access_type)&&!["DLC","Perm cath"].includes(_.access_type)||["AVF","AVG"].includes(a.access_type)&&!["AVF","AVG"].includes(_.access_type))&&(a.access_site_coagulant="",_.access_type=a.access_type),a.sodium_profile||(a.sodium_profile_start=null,a.sodium_profile_end=null),$("update:modelValue",a),$("autosave")},{deep:!0});const s=f({...x.formConfigs});l.anticoagulant&&s.anticoagulants.findIndex(a=>a.value===l.anticoagulant)===-1&&s.anticoagulants.push({value:l.anticoagulant,label:l.anticoagulant});const c=D(null);w(()=>l.anticoagulant,a=>{a.toLowerCase()==="other"&&(g.placeholder="Other anticoagulant",g.configs=s.anticoagulants,g.input=c.value,U.value.open())});const{selectOtherInput:U,selectOther:g,selectOtherClosed:O}=h(),t=f({heparin_loading_dose:null,heparin_maintenance_dose:null,enoxaparin_dose:null,tinzaparin_dose:null,ultrafiltration:null}),p=a=>{let e=s.validators[a];const o=e.type==="integer"?parseInt(l[a]):parseFloat(l[a]);o<e.min||o>e.max?(t[a]=`${l[a]} could not be saved. Accept range [${e.min}, ${e.max}].`,l[a]=null):t[a]=""};return(a,e)=>(m(),u(L,null,[d("h2",j,[e[45]||(e[45]=d("span",{class:"form-label !mb-0 text-lg italic text-complement"},"SLEDD Prescription",-1)),v.formConfigs.can.copy?(m(),u("button",{key:0,class:"flex items-center text-sm text-accent",onClick:e[0]||(e[0]=o=>a.$emit("copyPreviousOrder"))},[r(G,{class:"w-3 h-3 mr-1"}),e[44]||(e[44]=q(" Copy previous order "))])):y("",!0)]),e[50]||(e[50]=d("hr",{class:"my-4 border-b border-bitter-theme-light"},null,-1)),d("div",B,[d("div",null,[e[46]||(e[46]=d("label",{class:"form-label"},"duration (hrs.)",-1)),r(b,{class:"grid grid-cols-2 gap-x-2",name:"sledd.duration",error:a.$page.props.errors["sledd.duration"],modelValue:l.duration,"onUpdate:modelValue":e[1]||(e[1]=o=>l.duration=o),options:s.sledd_durations},null,8,["error","modelValue","options"])])]),d("div",E,[r(i,{label:"access type",modelValue:l.access_type,"onUpdate:modelValue":e[2]||(e[2]=o=>l.access_type=o),name:"sledd.access_type",options:s.access_types,error:a.$page.props.errors["sledd.access_type"]},null,8,["modelValue","options","error"]),r(i,{label:"access site",modelValue:l.access_site_coagulant,"onUpdate:modelValue":e[3]||(e[3]=o=>l.access_site_coagulant=o),name:"sledd.access_site_coagulant",options:l.access_type&&l.access_type.startsWith("AV")?s.av_access_sites:s.non_av_access_sites,disabled:!l.access_type||l.access_type==="pending",error:a.$page.props.errors["sledd.access_site_coagulant"]},null,8,["modelValue","options","disabled","error"]),r(i,{modelValue:l.dialyzer,"onUpdate:modelValue":e[4]||(e[4]=o=>l.dialyzer=o),name:"sledd.dialyzer",label:"dialyzer",options:s.sledd_dialyzers,error:a.$page.props.errors["sledd.dialyzer"]},null,8,["modelValue","options","error"]),r(i,{modelValue:l.dialysate,"onUpdate:modelValue":e[5]||(e[5]=o=>l.dialysate=o),name:"sledd.dialysate",label:"dialysate",options:s.dialysates,error:a.$page.props.errors["sledd.dialysate"]},null,8,["modelValue","options","error"]),r(i,{modelValue:l.blood_flow_rate,"onUpdate:modelValue":e[6]||(e[6]=o=>l.blood_flow_rate=o),name:"sledd.blood_flow_rate",options:s.sledd_blood_flow_rates,label:"blood flow rate (ml/min)",error:a.$page.props.errors["sledd.blood_flow_rate"]},null,8,["modelValue","options","error"]),r(i,{"model-value":l.dialysate_flow_rate,"onUpdate:modelValue":e[7]||(e[7]=o=>l.dialysate_flow_rate=o),"model-checkbox":l.reverse_dialysate_flow,"onUpdate:modelCheckbox":e[8]||(e[8]=o=>l.reverse_dialysate_flow=o),options:s.sledd_dialysate_flow_rates,name:"sledd.dialysate_flow_rate",label:"dialysate flow (ml/min)","switch-label":"Reverse flow",error:a.$page.props.errors["sledd.dialysate_flow_rate"]},null,8,["model-value","model-checkbox","options","error"]),r(i,{modelValue:l.dialysate_temperature,"onUpdate:modelValue":e[9]||(e[9]=o=>l.dialysate_temperature=o),name:"sledd.dialysate_temperature",options:s.dialysate_temperatures,label:"dialysate temperature (℃)",error:a.$page.props.errors["sledd.dialysate_temperature"]},null,8,["modelValue","options","error"])]),e[51]||(e[51]=d("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1)),d("div",R,[d("div",null,[r(n,{label:"sodium",name:"sledd.sodium",modelValue:l.sodium,"onUpdate:modelValue":e[10]||(e[10]=o=>l.sodium=o),type:"number",pattern:"\\d*",onAutosave:e[11]||(e[11]=o=>p("sodium")),error:t.sodium??a.$page.props.errors["sledd.sodium"],placeholder:`[${s.validators.sodium.min}, ${s.validators.sodium.max}]`},null,8,["modelValue","error","placeholder"]),r(V,{class:"mt-2 md:mt-4 xl:mt-8",label:"Sodium profile",name:"sledd.sodium_profile",modelValue:l.sodium_profile,"onUpdate:modelValue":e[12]||(e[12]=o=>l.sodium_profile=o),toggler:!0},null,8,["modelValue"]),r(z,{name:"slide-fade"},{default:k(()=>[l.sodium_profile?(m(),u("div",T,[r(n,{label:"start",name:"sledd.sodium_profile_start",modelValue:l.sodium_profile_start,"onUpdate:modelValue":e[13]||(e[13]=o=>l.sodium_profile_start=o),type:"number",pattern:"\\d*",error:t.sodium_profile_start??a.$page.props.errors["sledd.sodium_profile_start"]},null,8,["modelValue","error"]),r(n,{label:"end",name:"sledd.sodium_profile_end",modelValue:l.sodium_profile_end,"onUpdate:modelValue":e[14]||(e[14]=o=>l.sodium_profile_end=o),type:"number",pattern:"\\d*",error:t.sodium_profile_end??a.$page.props.errors["sledd.sodium_profile_end"]},null,8,["modelValue","error"])])):y("",!0)]),_:1})]),r(i,{modelValue:l.bicarbonate,"onUpdate:modelValue":e[15]||(e[15]=o=>l.bicarbonate=o),name:"sledd.bicarbonate",label:"bicarbonate",options:s.bicarbonates,error:a.$page.props.errors["sledd.bicarbonate"]},null,8,["modelValue","options","error"])]),e[52]||(e[52]=d("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1)),d("div",H,[r(i,{modelValue:l.anticoagulant,"onUpdate:modelValue":e[16]||(e[16]=o=>l.anticoagulant=o),name:"sledd.anticoagulant",label:"anticoagulant",options:s.anticoagulants,ref_key:"anticoagulantInput",ref:c,error:a.$page.props.errors["sledd.anticoagulant"]},null,8,["modelValue","options","error"])]),r(z,{name:"slide-fade"},{default:k(()=>[l.anticoagulant==="none"?(m(),u("div",W,[r(V,{label:"anticoagulant drip via peripheral IV",name:"anticoagulant_none_drip_via_peripheral_iv",modelValue:l.anticoagulant_none_drip_via_peripheral_iv,"onUpdate:modelValue":e[17]||(e[17]=o=>l.anticoagulant_none_drip_via_peripheral_iv=o)},null,8,["modelValue"]),r(V,{label:"NSS 200 ml flush q 1 hour",name:"anticoagulant_none_nss_200ml_flush_q_hour",modelValue:l.anticoagulant_none_nss_200ml_flush_q_hour,"onUpdate:modelValue":e[18]||(e[18]=o=>l.anticoagulant_none_nss_200ml_flush_q_hour=o)},null,8,["modelValue"])])):l.anticoagulant==="heparin"?(m(),u("div",J,[d("div",K,[r(n,{label:"loading dose (iu)",name:"sledd.heparin_loading_dose",modelValue:l.heparin_loading_dose,"onUpdate:modelValue":e[19]||(e[19]=o=>l.heparin_loading_dose=o),type:"number",pattern:"\\d*",onAutosave:e[20]||(e[20]=o=>p("heparin_loading_dose")),error:t.heparin_loading_dose??a.$page.props.errors["sledd.heparin_loading_dose"],placeholder:`[${s.validators.heparin_loading_dose.min}, ${s.validators.heparin_loading_dose.max}] IU`},null,8,["modelValue","error","placeholder"]),r(n,{label:"maintenance dose (iu/hour)",name:"sledd.heparin_maintenance_dose",modelValue:l.heparin_maintenance_dose,"onUpdate:modelValue":e[21]||(e[21]=o=>l.heparin_maintenance_dose=o),type:"number",pattern:"\\d*",onAutosave:e[22]||(e[22]=o=>p("heparin_maintenance_dose")),error:t.heparin_maintenance_dose??a.$page.props.errors["sledd.heparin_maintenance_dose"],placeholder:`[${s.validators.heparin_maintenance_dose.min}, ${s.validators.heparin_maintenance_dose.max}] IU/Hour`},null,8,["modelValue","error","placeholder"])]),r(F,{title:"Duration of maintenance (hours)",message:"DLC/PC uses duration of dialysis. AVF/AVG uses duration of dialysis - 1."})])):l.anticoagulant==="enoxaparin"?(m(),u("div",M,[r(n,{label:"dose (ml)",name:"sledd.enoxaparin_dose",modelValue:l.enoxaparin_dose,"onUpdate:modelValue":e[23]||(e[23]=o=>l.enoxaparin_dose=o),type:"number",onAutosave:e[24]||(e[24]=o=>p("enoxaparin_dose")),error:t.enoxaparin_dose??a.$page.props.errors["sledd.enoxaparin_dose"],placeholder:`[${s.validators.enoxaparin_dose.min}, ${s.validators.enoxaparin_dose.max}] ml`},null,8,["modelValue","error","placeholder"])])):l.anticoagulant==="fondaparinux"?(m(),u("div",Q,[r(i,{label:"bolus dose (iu)",name:"sledd.fondaparinux_bolus_dose",modelValue:l.fondaparinux_bolus_dose,"onUpdate:modelValue":e[25]||(e[25]=o=>l.fondaparinux_bolus_dose=o),options:s.fondaparinux_bolus_doses,error:a.$page.props.errors["sledd.fondaparinux_bolus_dose"]},null,8,["modelValue","options","error"])])):l.anticoagulant==="tinzaparin"?(m(),u("div",X,[r(n,{label:"dose (iu)",name:"sledd.tinzaparin_dose",modelValue:l.tinzaparin_dose,"onUpdate:modelValue":e[26]||(e[26]=o=>l.tinzaparin_dose=o),type:"number",pattern:"\\d*",onAutosave:e[27]||(e[27]=o=>p("tinzaparin_dose")),error:t.tinzaparin_dose??a.$page.props.errors["sledd.tinzaparin_dose"],placeholder:`[${s.validators.tinzaparin_dose.min}, ${s.validators.tinzaparin_dose.max}] IU`},null,8,["modelValue","error","placeholder"])])):y("",!0)]),_:1}),e[53]||(e[53]=d("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1)),d("div",Y,[d("div",null,[e[47]||(e[47]=d("label",{for:"",class:"form-label"},"uf (ml.)",-1)),d("div",Z,[r(n,{name:"sledd.ultrafiltration_min",modelValue:l.ultrafiltration_min,"onUpdate:modelValue":e[28]||(e[28]=o=>l.ultrafiltration_min=o),pattern:"\\d*",type:"number",onAutosave:e[29]||(e[29]=o=>p("ultrafiltration_min")),error:t.ultrafiltration_min??a.$page.props.errors["sledd.ultrafiltration_min"],placeholder:`min [${s.validators.ultrafiltration_min.min}, ${s.validators.ultrafiltration_min.max}]`},null,8,["modelValue","error","placeholder"]),r(n,{name:"sledd.ultrafiltration_max",modelValue:l.ultrafiltration_max,"onUpdate:modelValue":e[30]||(e[30]=o=>l.ultrafiltration_max=o),pattern:"\\d*",type:"number",onAutosave:e[31]||(e[31]=o=>p("ultrafiltration_max")),error:t.ultrafiltration_max??a.$page.props.errors["sledd.ultrafiltration_max"],placeholder:`max [${s.validators.ultrafiltration_max.min}, ${s.validators.ultrafiltration_max.max}]`},null,8,["modelValue","error","placeholder"])])]),r(n,{label:"dry weight (kg.)",modelValue:l.dry_weight,"onUpdate:modelValue":e[32]||(e[32]=o=>l.dry_weight=o),name:"sledd.dry_weight",type:"number",error:a.$page.props.errors["sledd.dry_weight"]},null,8,["modelValue","error"]),d("div",null,[e[48]||(e[48]=d("label",{class:"form-label"},"50% Glucose IV volume (ml)",-1)),r(b,{class:C(["grid grid-cols-2 gap-x-2",{"grid-cols-3":l.glucose_50_percent_iv_volume}]),name:"glucose_50_percent_iv_volume",modelValue:l.glucose_50_percent_iv_volume,"onUpdate:modelValue":e[33]||(e[33]=o=>l.glucose_50_percent_iv_volume=o),options:s.glucose_50_percent_iv_volumes,"allow-reset":!0},null,8,["class","modelValue","options"])]),r(i,{modelValue:l.glucose_50_percent_iv_at,"onUpdate:modelValue":e[34]||(e[34]=o=>l.glucose_50_percent_iv_at=o),name:"glucose_50_percent_iv_at",label:"50% glucose iv (at hour)",options:s.glucose_50_percent_iv_at},null,8,["modelValue","options"]),d("div",null,[e[49]||(e[49]=d("label",{class:"form-label"},"20% albumin prime (ml)",-1)),r(b,{class:C(["grid grid-cols-2 gap-x-2",{"grid-cols-3":l.albumin_20_percent_prime}]),name:"albumin_20_percent_prime",modelValue:l.albumin_20_percent_prime,"onUpdate:modelValue":e[35]||(e[35]=o=>l.albumin_20_percent_prime=o),options:s.albumin_20_percent_primes,"allow-reset":!0},null,8,["class","modelValue","options"])]),r(n,{label:"nutrition iv type",modelValue:l.nutrition_iv_type,"onUpdate:modelValue":e[36]||(e[36]=o=>l.nutrition_iv_type=o),name:"sledd.nutrition_iv_type",error:a.$page.props.errors["sledd.nutrition_iv_type"]},null,8,["modelValue","error"]),r(n,{label:"nutrition iv volume (ml)",modelValue:l.nutrition_iv_volume,"onUpdate:modelValue":e[37]||(e[37]=o=>l.nutrition_iv_volume=o),name:"sledd.nutrition_iv_volume",type:"number",pattern:"\\d*",error:a.$page.props.errors["sledd.nutrition_iv_volume"]},null,8,["modelValue","error"])]),e[54]||(e[54]=d("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1)),e[55]||(e[55]=d("label",{class:"form-label"},"transfusion :",-1)),d("div",N,[r(n,{label:"prc volume (unit)",name:"sledd.prc_volume",modelValue:l.prc_volume,"onUpdate:modelValue":e[38]||(e[38]=o=>l.prc_volume=o),type:"number",error:a.$page.props.errors["sledd.prc_volume"]},null,8,["modelValue","error"]),r(n,{label:"ffp volume (ml)",name:"sledd.ffp_volume",modelValue:l.ffp_volume,"onUpdate:modelValue":e[39]||(e[39]=o=>l.ffp_volume=o),type:"number",pattern:"\\d*",error:a.$page.props.errors["sledd.ffp_volume"]},null,8,["modelValue","error"]),r(n,{label:"platelet volume (unit)",name:"sledd.platelet_volume",modelValue:l.platelet_volume,"onUpdate:modelValue":e[40]||(e[40]=o=>l.platelet_volume=o),type:"number",error:a.$page.props.errors["sledd.platelet_volume"]},null,8,["modelValue","error"]),r(n,{label:"other",name:"sledd.transfusion_other",modelValue:l.transfusion_other,"onUpdate:modelValue":e[41]||(e[41]=o=>l.transfusion_other=o),error:a.$page.props.errors["sledd.transfusion_other"]},null,8,["modelValue","error"])]),r(P,{class:"mt-2 md:mt-4 xl:mt-8",label:"note",name:"sledd.remark",error:a.$page.props.errors["sledd.remark"],modelValue:l.remark,"onUpdate:modelValue":e[42]||(e[42]=o=>l.remark=o)},null,8,["error","modelValue"]),r(S,{placeholder:A(g).placeholder,ref_key:"selectOtherInput",ref:U,onClosed:e[43]||(e[43]=o=>A(O)(o,!0))},null,8,["placeholder"])],64))}};export{me as default};
