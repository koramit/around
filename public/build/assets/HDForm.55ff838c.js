import{s as V,E as D,r as R,o as u,c as p,F as j,a as n,b as r,i as h,w as G,T as H,af as W,k as L,u as S,d as T}from"./app.3be387f2.js";import{_ as x}from"./FormCheckbox.16a502e2.js";import{_ as s}from"./FormInput.a6edf12a.js";import{_ as m}from"./FormSelect.f2a03d04.js";import{u as J,_ as K}from"./FormSelectOther.d6ac8e82.js";import{_ as $}from"./FormRadio.43612332.js";import{I as B}from"./IconCopy.5e147825.js";import"./ModalDialog.963dca7a.js";const M={class:"mt-6 md:mt-12 xl:mt-24 flex justify-between items-center",id:"prescription"},Q=n("span",{class:"form-label !mb-0 text-lg italic text-complement"},"HF Prescription",-1),X=T(" Copy previous order "),Y=n("hr",{class:"my-4 border-b border-accent"},null,-1),Z={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},N=n("label",{class:"form-label"},"perform at :",-1),ee=n("label",{for:"",class:"form-label"},"uf (ml.) :",-1),oe={class:"grid gap-2 md:grid-cols-2"},le={class:"mt-6 md:mt-12 xl:mt-24 flex justify-between items-center",id:"prescription"},ae=n("span",{class:"form-label !mb-0 text-lg italic text-complement"},"HD Prescription",-1),re=T(" Copy previous order "),te=n("hr",{class:"my-4 border-b border-accent"},null,-1),ne={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},se=n("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ie={class:"grid md:grid-cols-2 gap-2 xl:gap-8 my-2 md:my-4 xl:mt-8"},de={key:0,class:"grid gap-2 md:gap-4 xl:gap-8 mt-2 md:mt-4 xl:mt-8"},me=n("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ue={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},pe={key:0,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},_e={key:1},ge={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},fe={key:2,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},ye={key:3,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},ce={key:4,class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4 my-2 md:my-4 xl:my-8"},he=n("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ve={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},be=n("label",{for:"",class:"form-label"},"uf (ml.)",-1),Ve={class:"grid gap-2 md:grid-cols-2"},xe=n("label",{class:"form-label"},"50% Glucose IV volume (ml)",-1),$e=n("label",{class:"form-label"},"20% albumin prime (ml)",-1),Ue=n("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),we=n("label",{class:"form-label"},"transfusion :",-1),ke={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},De={__name:"HDForm",props:{modelValue:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},emits:["update:modelValue","autosave","copyPreviousOrder"],setup(b,{emit:U}){var z,A;const w=b,o=V({...w.modelValue}),f={anticoagulant:(z=o.anticoagulant)!=null?z:null,access_type:(A=o.access_type)!=null?A:null};D(()=>o,a=>{a.anticoagulant!==f.anticoagulant&&(a.anticoagulant_none_drip_via_peripheral_iv=!1,a.anticoagulant_none_nss_200ml_flush_q_hour=!1,a.heparin_loading_dose=null,a.heparin_maintenance_dose=null,a.enoxaparin_dose=null,a.fondaparinux_bolus_dose=null,a.tinzaparin_dose=null,f.anticoagulant=a.anticoagulant),(a.access_type==="pending"||a.access_type==="Remove"||["DLC","Perm cath"].includes(a.access_type)&&!["DLC","Perm cath"].includes(f.access_type)||["AVF","AVG"].includes(a.access_type)&&!["AVF","AVG"].includes(f.access_type))&&(a.access_site_coagulant=null,f.access_type=a.access_type),a.sodium_profile||(a.sodium_profile_start=null,a.sodium_profile_end=null),U("update:modelValue",a),U("autosave")},{deep:!0});const t=V({...w.formConfigs});o.anticoagulant&&t.anticoagulants.findIndex(a=>a.value===o.anticoagulant)===-1&&t.anticoagulants.push({value:o.anticoagulant,label:o.anticoagulant});const k=R(null);D(()=>o.anticoagulant,a=>{a.toLowerCase()==="other"&&(v.placeholder="Other anticoagulant",v.configs=t.anticoagulants,v.input=k.value,C.value.open())});const{selectOtherInput:C,selectOther:v,selectOtherClosed:E}=J(),d=V({heparin_loading_dose:null,heparin_maintenance_dose:null,enoxaparin_dose:null,tinzaparin_dose:null,ultrafiltration:null}),_=a=>{let e=t.validators[a];const y=e.type==="integer"?parseInt(o[a]):parseFloat(o[a]);y<e.min||y>e.max?(d[a]=`${o[a]} could not be saved. Accept range [${e.min}, ${e.max}].`,o[a]=null):d[a]=""};return(a,e)=>{var y,I,O,F,P;return u(),p(j,null,[o.hf_perform_at!==void 0?(u(),p(j,{key:0},[n("h2",M,[Q,b.formConfigs.can.copy?(u(),p("button",{key:0,class:"flex items-center text-sm text-accent",onClick:e[0]||(e[0]=l=>a.$emit("copyPreviousOrder"))},[r(B,{class:"w-3 h-3 mr-1"}),X])):h("",!0)]),Y,n("div",Z,[n("div",null,[N,r($,{class:"grid grid-cols-2 gap-x-2",name:"hd.hf_perform_at",modelValue:o.hf_perform_at,"onUpdate:modelValue":e[1]||(e[1]=l=>o.hf_perform_at=l),options:t.hf_perform_at,error:a.$page.props.errors["hd.hf_perform_at"]},null,8,["modelValue","options","error"])]),n("div",null,[ee,n("div",oe,[r(s,{name:"hd.hf_ultrafiltration_min",modelValue:o.hf_ultrafiltration_min,"onUpdate:modelValue":e[2]||(e[2]=l=>o.hf_ultrafiltration_min=l),pattern:"\\d*",type:"number",onAutosave:e[3]||(e[3]=l=>_("hf_ultrafiltration_min")),error:(y=d.hf_ultrafiltration_min)!=null?y:a.$page.props.errors["hd.hf_ultrafiltration_min"],placeholder:`min [${t.validators.hf_ultrafiltration_min.min}, ${t.validators.hf_ultrafiltration_min.max}]`},null,8,["modelValue","error","placeholder"]),r(s,{name:"hd.hf_ultrafiltration_max",modelValue:o.hf_ultrafiltration_max,"onUpdate:modelValue":e[4]||(e[4]=l=>o.hf_ultrafiltration_max=l),pattern:"\\d*",type:"number",onAutosave:e[5]||(e[5]=l=>_("hf_ultrafiltration_max")),error:(I=d.hf_ultrafiltration_max)!=null?I:a.$page.props.errors["hd.hf_ultrafiltration_max"],placeholder:`max [${t.validators.hf_ultrafiltration_max.min}, ${t.validators.hf_ultrafiltration_max.max}]`},null,8,["modelValue","error","placeholder"])])])])],64)):h("",!0),n("h2",le,[ae,o.hf_perform_at===void 0&&b.formConfigs.can.copy?(u(),p("button",{key:0,class:"flex items-center text-sm text-accent",onClick:e[6]||(e[6]=l=>a.$emit("copyPreviousOrder"))},[r(B,{class:"w-3 h-3 mr-1"}),re])):h("",!0)]),te,n("div",ne,[r(m,{label:"access type",modelValue:o.access_type,"onUpdate:modelValue":e[7]||(e[7]=l=>o.access_type=l),name:"hd.access_type",options:t.access_types,error:a.$page.props.errors["hd.access_type"]},null,8,["modelValue","options","error"]),r(m,{label:"access site",modelValue:o.access_site_coagulant,"onUpdate:modelValue":e[8]||(e[8]=l=>o.access_site_coagulant=l),name:"hd.access_site_coagulant",options:o.access_type&&o.access_type.startsWith("AV")?t.av_access_sites:t.non_av_access_sites,disabled:!o.access_type||o.access_type==="pending",error:a.$page.props.errors["hd.access_site_coagulant"]},null,8,["modelValue","options","disabled","error"]),r(m,{modelValue:o.dialyzer,"onUpdate:modelValue":e[9]||(e[9]=l=>o.dialyzer=l),name:"hd.dialyzer",label:"dialyzer",options:t.dialyzers,error:a.$page.props.errors["hd.dialyzer"]},null,8,["modelValue","options","error"]),r(m,{modelValue:o.dialysate,"onUpdate:modelValue":e[10]||(e[10]=l=>o.dialysate=l),name:"hd.dialysate",label:"dialysate",options:t.dialysates,error:a.$page.props.errors["hd.dialysate"]},null,8,["modelValue","options","error"]),r(m,{modelValue:o.blood_flow_rate,"onUpdate:modelValue":e[11]||(e[11]=l=>o.blood_flow_rate=l),name:"hd.blood_flow_rate",options:t.blood_flow_rates,label:"blood flow rate (ml/min)",error:a.$page.props.errors["hd.blood_flow_rate"]},null,8,["modelValue","options","error"]),r(m,{"model-value":o.dialysate_flow_rate,"onUpdate:model-value":e[12]||(e[12]=l=>o.dialysate_flow_rate=l),"model-checkbox":o.reverse_dialysate_flow,"onUpdate:model-checkbox":e[13]||(e[13]=l=>o.reverse_dialysate_flow=l),options:t.dialysate_flow_rates,name:"hd.dialysate_flow_rate",label:"dialysate flow (ml/min)","switch-label":"Reverse flow",error:a.$page.props.errors["hd.dialysate_flow_rate"]},null,8,["model-value","model-checkbox","options","error"]),r(m,{modelValue:o.dialysate_temperature,"onUpdate:modelValue":e[14]||(e[14]=l=>o.dialysate_temperature=l),name:"hd.dialysate_temperature",options:t.dialysate_temperatures,label:"dialysate temperature (\u2103)",error:a.$page.props.errors["hd.dialysate_temperature"]},null,8,["modelValue","options","error"])]),se,n("div",ie,[n("div",null,[r(s,{label:"sodium",name:"hd.sodium",modelValue:o.sodium,"onUpdate:modelValue":e[15]||(e[15]=l=>o.sodium=l),type:"number",pattern:"\\d*",onAutosave:e[16]||(e[16]=l=>_("sodium")),error:(O=d.sodium)!=null?O:a.$page.props.errors["hd.sodium"],placeholder:`[${t.validators.sodium.min}, ${t.validators.sodium.max}]`},null,8,["modelValue","error","placeholder"]),r(x,{class:"mt-2 md:mt-4 xl:mt-8",label:"Sodium profile",name:"hd.sodium_profile",modelValue:o.sodium_profile,"onUpdate:modelValue":e[17]||(e[17]=l=>o.sodium_profile=l),toggler:!0},null,8,["modelValue"]),r(H,{name:"slide-fade"},{default:G(()=>{var l,c;return[o.sodium_profile?(u(),p("div",de,[r(s,{label:"start",name:"hd.sodium_profile_start",modelValue:o.sodium_profile_start,"onUpdate:modelValue":e[18]||(e[18]=g=>o.sodium_profile_start=g),type:"number",pattern:"\\d*",error:(l=d.sodium_profile_start)!=null?l:a.$page.props.errors["hd.sodium_profile_start"]},null,8,["modelValue","error"]),r(s,{label:"end",name:"hd.sodium_profile_end",modelValue:o.sodium_profile_end,"onUpdate:modelValue":e[19]||(e[19]=g=>o.sodium_profile_end=g),type:"number",pattern:"\\d*",error:(c=d.sodium_profile_end)!=null?c:a.$page.props.errors["hd.sodium_profile_end"]},null,8,["modelValue","error"])])):h("",!0)]}),_:1})]),r(m,{modelValue:o.bicarbonate,"onUpdate:modelValue":e[20]||(e[20]=l=>o.bicarbonate=l),name:"hd.bicarbonate",label:"bicarbonate",options:t.bicarbonates,error:a.$page.props.errors["hd.bicarbonate"]},null,8,["modelValue","options","error"])]),me,n("div",ue,[r(m,{modelValue:o.anticoagulant,"onUpdate:modelValue":e[21]||(e[21]=l=>o.anticoagulant=l),name:"hd.anticoagulant",label:"anticoagulant",options:t.anticoagulants,ref_key:"anticoagulantInput",ref:k,error:a.$page.props.errors["hd.anticoagulant"]},null,8,["modelValue","options","error"])]),r(H,{name:"slide-fade"},{default:G(()=>{var l,c,g,q;return[o.anticoagulant==="none"?(u(),p("div",pe,[r(x,{label:"anticoagulant drip via peripheral IV",name:"anticoagulant_none_drip_via_peripheral_iv",modelValue:o.anticoagulant_none_drip_via_peripheral_iv,"onUpdate:modelValue":e[22]||(e[22]=i=>o.anticoagulant_none_drip_via_peripheral_iv=i)},null,8,["modelValue"]),r(x,{label:"NSS 200 ml flush q 1 hour",name:"anticoagulant_none_nss_200ml_flush_q_hour",modelValue:o.anticoagulant_none_nss_200ml_flush_q_hour,"onUpdate:modelValue":e[23]||(e[23]=i=>o.anticoagulant_none_nss_200ml_flush_q_hour=i)},null,8,["modelValue"])])):o.anticoagulant==="heparin"?(u(),p("div",_e,[n("div",ge,[r(s,{label:"loading dose (iu)",name:"hd.heparin_loading_dose",modelValue:o.heparin_loading_dose,"onUpdate:modelValue":e[24]||(e[24]=i=>o.heparin_loading_dose=i),type:"number",pattern:"\\d*",onAutosave:e[25]||(e[25]=i=>_("heparin_loading_dose")),error:(l=d.heparin_loading_dose)!=null?l:a.$page.props.errors["hd.heparin_loading_dose"],placeholder:`[${t.validators.heparin_loading_dose.min}, ${t.validators.heparin_loading_dose.max}] IU`},null,8,["modelValue","error","placeholder"]),r(s,{label:"maintenance dose (iu/hour)",name:"hd.heparin_maintenance_dose",modelValue:o.heparin_maintenance_dose,"onUpdate:modelValue":e[26]||(e[26]=i=>o.heparin_maintenance_dose=i),type:"number",pattern:"\\d*",onAutosave:e[27]||(e[27]=i=>_("heparin_maintenance_dose")),error:(c=d.heparin_maintenance_dose)!=null?c:a.$page.props.errors["hd.heparin_maintenance_dose"],placeholder:`[${t.validators.heparin_maintenance_dose.min}, ${t.validators.heparin_maintenance_dose.max}] IU/Hour`},null,8,["modelValue","error","placeholder"])]),r(W,{title:"Duration of maintenance (hours)",message:"DLC/PC uses duration of dialysis. AVF/AVG uses duration of dialysis - 1."})])):o.anticoagulant==="enoxaparin"?(u(),p("div",fe,[r(s,{label:"dose (ml)",name:"hd.enoxaparin_dose",modelValue:o.enoxaparin_dose,"onUpdate:modelValue":e[28]||(e[28]=i=>o.enoxaparin_dose=i),type:"number",onAutosave:e[29]||(e[29]=i=>_("enoxaparin_dose")),error:(g=d.enoxaparin_dose)!=null?g:a.$page.props.errors["hd.enoxaparin_dose"],placeholder:`[${t.validators.enoxaparin_dose.min}, ${t.validators.enoxaparin_dose.max}] ml`},null,8,["modelValue","error","placeholder"])])):o.anticoagulant==="fondaparinux"?(u(),p("div",ye,[r(m,{label:"bolus dose (iu)",name:"hd.fondaparinux_bolus_dose",modelValue:o.fondaparinux_bolus_dose,"onUpdate:modelValue":e[30]||(e[30]=i=>o.fondaparinux_bolus_dose=i),options:t.fondaparinux_bolus_doses,error:a.$page.props.errors["hd.fondaparinux_bolus_dose"]},null,8,["modelValue","options","error"])])):o.anticoagulant==="tinzaparin"?(u(),p("div",ce,[r(s,{label:"dose (iu)",name:"hd.tinzaparin_dose",modelValue:o.tinzaparin_dose,"onUpdate:modelValue":e[31]||(e[31]=i=>o.tinzaparin_dose=i),type:"number",pattern:"\\d*",onAutosave:e[32]||(e[32]=i=>_("tinzaparin_dose")),error:(q=d.tinzaparin_dose)!=null?q:a.$page.props.errors["hd.tinzaparin_dose"],placeholder:`[${t.validators.tinzaparin_dose.min}, ${t.validators.tinzaparin_dose.max}] IU`},null,8,["modelValue","error","placeholder"])])):h("",!0)]}),_:1}),he,n("div",ve,[n("div",null,[be,n("div",Ve,[r(s,{name:"hd.ultrafiltration_min",modelValue:o.ultrafiltration_min,"onUpdate:modelValue":e[33]||(e[33]=l=>o.ultrafiltration_min=l),pattern:"\\d*",type:"number",onAutosave:e[34]||(e[34]=l=>_("ultrafiltration_min")),error:(F=d.ultrafiltration_min)!=null?F:a.$page.props.errors["hd.ultrafiltration_min"],placeholder:`min [${t.validators.ultrafiltration_min.min}, ${t.validators.ultrafiltration_min.max}]`},null,8,["modelValue","error","placeholder"]),r(s,{name:"hd.ultrafiltration_max",modelValue:o.ultrafiltration_max,"onUpdate:modelValue":e[35]||(e[35]=l=>o.ultrafiltration_max=l),pattern:"\\d*",type:"number",onAutosave:e[36]||(e[36]=l=>_("ultrafiltration_max")),error:(P=d.ultrafiltration_max)!=null?P:a.$page.props.errors["hd.ultrafiltration_max"],placeholder:`max [${t.validators.ultrafiltration_max.min}, ${t.validators.ultrafiltration_max.max}]`},null,8,["modelValue","error","placeholder"])])]),r(s,{label:"dry weight (kg.)",modelValue:o.dry_weight,"onUpdate:modelValue":e[37]||(e[37]=l=>o.dry_weight=l),name:"hd.dry_weight",type:"number",error:a.$page.props.errors["hd.dry_weight"]},null,8,["modelValue","error"]),n("div",null,[xe,r($,{class:L(["grid grid-cols-2 gap-x-2",{"grid-cols-3":o.glucose_50_percent_iv_volume}]),name:"glucose_50_percent_iv_volume",modelValue:o.glucose_50_percent_iv_volume,"onUpdate:modelValue":e[38]||(e[38]=l=>o.glucose_50_percent_iv_volume=l),options:t.glucose_50_percent_iv_volumes,"allow-reset":!0},null,8,["class","modelValue","options"])]),r(m,{modelValue:o.glucose_50_percent_iv_at,"onUpdate:modelValue":e[39]||(e[39]=l=>o.glucose_50_percent_iv_at=l),name:"glucose_50_percent_iv_at",label:"50% glucose iv (at hour)",options:[...t.glucose_50_percent_iv_at]},null,8,["modelValue","options"]),n("div",null,[$e,r($,{class:L(["grid grid-cols-2 gap-x-2",{"grid-cols-3":o.albumin_20_percent_prime}]),name:"albumin_20_percent_prime",modelValue:o.albumin_20_percent_prime,"onUpdate:modelValue":e[40]||(e[40]=l=>o.albumin_20_percent_prime=l),options:t.albumin_20_percent_primes,"allow-reset":!0},null,8,["class","modelValue","options"])]),r(s,{label:"nutrition iv type",modelValue:o.nutrition_iv_type,"onUpdate:modelValue":e[41]||(e[41]=l=>o.nutrition_iv_type=l),name:"hd.nutrition_iv_type",error:a.$page.props.errors["hd.nutrition_iv_type"]},null,8,["modelValue","error"]),r(s,{label:"nutrition iv volume (ml)",modelValue:o.nutrition_iv_volume,"onUpdate:modelValue":e[42]||(e[42]=l=>o.nutrition_iv_volume=l),name:"hd.nutrition_iv_volume",type:"number",pattern:"\\d*",error:a.$page.props.errors["hd.nutrition_iv_volume"]},null,8,["modelValue","error"])]),Ue,we,n("div",ke,[r(s,{label:"prc volume (unit)",name:"hd.prc_volume",modelValue:o.prc_volume,"onUpdate:modelValue":e[43]||(e[43]=l=>o.prc_volume=l),type:"number",error:a.$page.props.errors["hd.prc_volume"]},null,8,["modelValue","error"]),r(s,{label:"ffp volume (ml)",name:"hd.ffp_volume",modelValue:o.ffp_volume,"onUpdate:modelValue":e[44]||(e[44]=l=>o.ffp_volume=l),type:"number",pattern:"\\d*",error:a.$page.props.errors["hd.ffp_volume"]},null,8,["modelValue","error"]),r(s,{label:"platelet volume (unit)",name:"hd.platelet_volume",modelValue:o.platelet_volume,"onUpdate:modelValue":e[45]||(e[45]=l=>o.platelet_volume=l),type:"number",error:a.$page.props.errors["hd.platelet_volume"]},null,8,["modelValue","error"]),r(s,{label:"other",name:"hd.transfusion_other",modelValue:o.transfusion_other,"onUpdate:modelValue":e[46]||(e[46]=l=>o.transfusion_other=l),error:a.$page.props.errors["hd.transfusion_other"]},null,8,["modelValue","error"])]),r(K,{placeholder:S(v).placeholder,ref_key:"selectOtherInput",ref:C,onClosed:e[47]||(e[47]=l=>S(E)(l,!0))},null,8,["placeholder"])],64)}}};export{De as default};
