import{r as F,b as _,c,d as a,t as I,k as b,e as s,h as p,I as z,T as N,y as G,z as T,F as f,u as l,f as $,q as B,m as C,s as v,g as D,A as Y,B as K,C as M}from"./app-dd3bacbb.js";import{u as J}from"./useConfirmForm-144edf77.js";import{_ as u}from"./FormInput-90a1e0a7.js";import{_ as E}from"./FormSelect-5dc8ce45.js";import{_ as k}from"./FormDatetime-94d714b3.js";import{_ as Q}from"./FormAutocomplete-3eb3c3e0.js";import{_ as W}from"./FormTextarea-aa4f06a2.js";import{_ as h}from"./FormRadio-bebd229e.js";import{I as X}from"./IconPaperclip-d2d040ce.js";import{I as Z}from"./IconEyes-193dbea2.js";import{_ as S}from"./SpinnerButton-1601a823.js";import"./throttle-3a67ded0.js";const ee=["id"],oe=["name"],le={class:"flex items-center"},ae={class:"form-label !mb-0"},te=["href"],se={key:0,class:"text-red-700 text-sm"},de={__name:"FileUploader",props:{modelValue:{type:String,default:""},label:{type:String,required:!0},pathname:{type:String,required:!0},error:{type:String,default:""},readonly:{type:Boolean},serviceEndpoints:{type:Object,required:!0},name:{type:String,required:!0}},emits:["update:modelValue","autosave"],setup(d,{emit:q}){const i=q,r=d,o=F(null),U=F(!1),O=F(r.modelValue),x=A=>{const y=A.target.files[0];if(!y)return;const g=new FormData;g.append("file",y),g.append("pathname",r.pathname),g.append("old",r.modelValue),U.value=!0,window.axios.post(r.serviceEndpoints.store,g).then(V=>{O.value=V.data.filename,i("update:modelValue",V.data.filename),i("autosave")}).catch(V=>{console.log(V)}).finally(()=>{U.value=!1})};return(A,y)=>(_(),c("div",{id:d.name},[a("input",{type:"file",class:"hidden",onChange:x,ref_key:"fileInput",ref:o,accept:"application/pdf, image/jpeg, image/png",name:d.name},null,40,oe),a("div",le,[a("span",ae,I(d.label),1),U.value?(_(),b(z,{key:0,class:"ml-2 w-4 h-4 inline-block opacity-25 animate-spin"})):(_(),c("button",{key:1,onClick:y[0]||(y[0]=g=>o.value.click())},[s(X,{class:"ml-2 w-4 h-4"})])),d.modelValue?(_(),c("a",{key:2,class:"ml-2",target:"_blank",href:`${d.serviceEndpoints.show}?path=${d.pathname}/${O.value}`},[s(Z,{class:"w-4 h-4 text-dark-theme-light"})],8,te)):p("",!0)]),d.error?(_(),c("div",se,I(d.error),1)):p("",!0)],8,ee))}},re=a("h2",{class:"form-label text-lg italic text-complement scroll-mt-16 md:scroll-mt-8",id:"case-record"}," HLA typing for Kidney Transplant Report ",-1),ne=a("hr",{class:"my-4 border-b border-accent"},null,-1),me={class:"grid gap-2 md:grid md:gap-4 md:grid-cols-2 xl:gap-8"},_e=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ce={key:0},ie=a("h3",{class:"form-label mt-4 md:mt-8 xl:mt-16"}," HLA TYPING : ",-1),ue={key:0,class:"form-label underline"},pe={class:"grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"},he=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ye=a("label",{class:"form-label italic"},"class i",-1),ge={class:"grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"},fe=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),be=a("label",{class:"form-label italic"},"class ii",-1),Ve={class:"grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"},$e=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),xe={key:0},Ce=a("h3",{class:"form-label mt-4 md:mt-8 xl:mt-16"}," lymphocyte crossmatch : ",-1),ve={key:0,class:"form-label underline"},Ue=a("label",{class:"form-label italic"},"t-lymphocyte",-1),we={class:"md:flex md:space-x-2"},ke={class:"md:w-4/6 p-1 md:p-2 bg-white rounded"},Ie=a("label",{class:"form-label text-center"},"cdc",-1),Oe={class:"md:flex space-y-1 md:space-y-0 md:space-x-2"},Ae={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},De=a("label",{class:"form-label text-center"},"neat",-1),Ee={class:"flex"},Se={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},qe=a("label",{class:"form-label text-center"},"dtt",-1),Fe={class:"flex"},Ne={class:"mt-4 md:w-2/6 md:mt-0 p-1 md:p-2 bg-white rounded"},Te=a("label",{class:"form-label text-center"},"cdc - ahg",-1),Be={class:"flex space-x-1 md:space-x-2"},Le={class:"w-1/2 p-1 md:p-2 bg-primary rounded"},Pe=a("label",{class:"form-label text-center"},"neat",-1),je={class:"w-1/2 p-1 md:p-2 bg-primary rounded"},He=a("label",{class:"form-label text-center"},"dtt",-1),Re=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ze=a("label",{class:"form-label italic"},"b-lymphocyte",-1),Ge={class:"flex space-x-1 md:space-x-2"},Ye={class:"w-1/2 p-1 md:p-2 bg-white rounded"},Ke=a("label",{class:"form-label text-center"},"cdc",-1),Me={class:"md:flex space-y-1 md:space-y-0 md:space-x-2"},Je={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},Qe=a("label",{class:"form-label text-center"},"neat",-1),We={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},Xe=a("label",{class:"form-label text-center"},"dtt",-1),Ze={class:"w-1/2 p-1 md:p-2 bg-white rounded"},eo=a("label",{class:"form-label text-center"},"cdc - ahg",-1),oo={class:"md:flex space-y-1 md:space-y-0 md:space-x-2"},lo={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},ao=a("label",{class:"form-label text-center"},"neat",-1),to={class:"md:w-1/2 p-1 md:p-2 bg-primary rounded"},so=a("label",{class:"form-label text-center"},"dtt",-1),ro=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),no=a("label",{class:"form-label italic"},"flow cytometry crossmatch",-1),mo={key:0},_o=a("h3",{class:"form-label mt-4 md:mt-8 xl:mt-16"}," ADDITION TISSUE TYPING : ",-1),co={key:0,class:"form-label underline"},io=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),uo={class:"grid gap-2 md:grid-cols-2 md:gap-4 xl:gap-6"},po=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),ho=a("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Io={__name:"ReportEdit",props:{metaData:{type:Object,required:!0},formData:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},setup(d){const q=Y(()=>M(()=>import("./ConfirmFormComposable-e06263a9.js"),["assets/ConfirmFormComposable-e06263a9.js","assets/FormInput-90a1e0a7.js","assets/app-dd3bacbb.js","assets/app-a2e48b5b.css","assets/SpinnerButton-1601a823.js","assets/ModalDialog-1b82f704.js","assets/ModalDialog-10546d4c.css"])),i=d,r={...i.metaData},o=N({...i.formData}),{autosave:U}=G();T(()=>o.data(),m=>{U(m,i.formConfigs.routes.update)},{deep:i.formConfigs.can.update}),T(()=>o.recipient_is,m=>{o.donor_is=null,i.formConfigs.donor_is_options[m].length===1&&(o.donor_is=i.formConfigs.donor_is_options[m][0])});const{actionStore:O}=K();let x=null;T(()=>O.value,m=>{switch(m.name){case"publish-report":g();break;case"addendum-report":V();break;case"destroy-report":case"cancel-report":x=m.name,L(m.config);break;default:return}},{deep:!0});const A=m=>{switch(x){case"destroy-report":P(m);break;case"cancel-report":j(m);break;default:return}x=null},y=m=>{x=m,L(i.formConfigs.actions.find(n=>n.name===m).config)},g=()=>o.post(i.formConfigs.routes.publish),V=()=>o.put(i.formConfigs.routes.addendum),P=m=>N({reason:m}).delete(i.formConfigs.routes.destroy),j=m=>N({reason:m}).delete(i.formConfigs.routes.cancel),{confirmForm:H,openConfirmForm:L,confirmed:R}=J();return(m,n)=>(_(),c(f,null,[re,ne,a("div",me,[s(u,{label:r.donor_hn?"recipient hn":"patient hn",readonly:!0,name:"patient_hn",modelValue:r.patient_hn,"onUpdate:modelValue":n[0]||(n[0]=e=>r.patient_hn=e)},null,8,["label","modelValue"]),s(u,{label:r.donor_hn?"recipient name":"patient name",readonly:!0,name:"patient_name",modelValue:r.patient_name,"onUpdate:modelValue":n[1]||(n[1]=e=>r.patient_name=e)},null,8,["label","modelValue"]),r.donor_hn?(_(),c(f,{key:0},[s(u,{label:"donor hn",readonly:!0,name:"donor_hn",modelValue:r.donor_hn,"onUpdate:modelValue":n[2]||(n[2]=e=>r.donor_hn=e)},null,8,["modelValue"]),s(u,{label:"donor name",readonly:!0,name:"donor_name",modelValue:r.donor_name,"onUpdate:modelValue":n[3]||(n[3]=e=>r.donor_name=e)},null,8,["modelValue"]),s(E,{label:"recipient is",name:"recipient_is",options:d.formConfigs.recipient_is_options,modelValue:l(o).recipient_is,"onUpdate:modelValue":n[4]||(n[4]=e=>l(o).recipient_is=e),error:m.$page.props.errors.recipient_is},null,8,["options","modelValue","error"]),s(E,{disabled:!l(o).recipient_is,label:"donor is",name:"donor_is",options:d.formConfigs.donor_is_options[l(o).recipient_is]??[],modelValue:l(o).donor_is,"onUpdate:modelValue":n[5]||(n[5]=e=>l(o).donor_is=e),error:m.$page.props.errors.donor_is},null,8,["disabled","options","modelValue","error"])],64)):p("",!0),s(u,{label:"diagnosis",name:"diagnosis",modelValue:l(o).diagnosis,"onUpdate:modelValue":n[6]||(n[6]=e=>l(o).diagnosis=e)},null,8,["modelValue"]),s(Q,{label:"clinician",name:"clinician",modelValue:l(o).clinician,"onUpdate:modelValue":n[7]||(n[7]=e=>l(o).clinician=e),endpoint:d.formConfigs.routes.clinicians,params:d.formConfigs.routes.clinicians_scope_params,error:m.$page.props.errors.clinician,"length-to-start":3},null,8,["modelValue","endpoint","params","error"]),s(k,{label:"collection date",name:"date_serum",modelValue:r.date_serum,"onUpdate:modelValue":n[8]||(n[8]=e=>r.date_serum=e),disabled:!0},null,8,["modelValue"]),s(k,{label:"report date",name:"date_report",modelValue:l(o).date_report,"onUpdate:modelValue":n[9]||(n[9]=e=>l(o).date_report=e),error:m.$page.props.errors.date_report},null,8,["modelValue","error"]),s(u,{label:"report by",name:"reporter",modelValue:l(o).reporter,"onUpdate:modelValue":n[10]||(n[10]=e=>l(o).reporter=e),error:m.$page.props.errors.reporter},null,8,["modelValue","error"]),s(u,{label:"approve by",name:"approver",modelValue:l(o).approver,"onUpdate:modelValue":n[11]||(n[11]=e=>l(o).approver=e),error:m.$page.props.errors.approver},null,8,["modelValue","error"])]),_e,s(B,{name:"slide-fade"},{default:$(()=>[r.request_hla?(_(),c("div",ce,[ie,a("div",{class:v(["mt-4 gap-2 md:grid-cols-2 md:gap-4 xl:gap-6",{grid:r.donor_hn}])},[(_(!0),c(f,null,C(r.patients,e=>(_(),c("div",{key:e,class:v(["space-y-2 md:space-y-4",{"border-green-400 p-2 md:p-4 rounded-lg border-2":e==="patient"&&r.donor_hn,"border-amber-400 p-2 md:p-4 rounded-lg border-2":e==="donor"}])},[r.donor_hn?(_(),c("h4",ue,I(r.donor_hn&&e==="patient"?"recipient":e),1)):p("",!0),s(k,{label:"date test",name:`${e}_date_hla_typing`,modelValue:l(o)[`${e}_hla_note`].date_hla_typing,"onUpdate:modelValue":t=>l(o)[`${e}_hla_note`].date_hla_typing=t,error:m.$page.props.errors[`${e}_hla_note.date_hla_typing`]},null,8,["name","modelValue","onUpdate:modelValue","error"]),a("div",pe,[s(E,{label:"abo",name:`${e}_abo`,modelValue:l(o)[`${e}_hla_note`].abo,"onUpdate:modelValue":t=>l(o)[`${e}_hla_note`].abo=t,options:d.formConfigs.abo_options,error:m.$page.props.errors[`${e}_hla_note.abo`]},null,8,["name","modelValue","onUpdate:modelValue","options","error"]),s(E,{label:"rh",name:`${e}_rh`,modelValue:l(o)[`${e}_hla_note`].rh,"onUpdate:modelValue":t=>l(o)[`${e}_hla_note`].rh=t,options:d.formConfigs.rh_options,error:m.$page.props.errors[`${e}_hla_note.rh`]},null,8,["name","modelValue","onUpdate:modelValue","options","error"])]),he,ye,a("div",ge,[(_(!0),c(f,null,C(d.formConfigs.antigens,t=>(_(),b(u,{key:t.name,label:t.label,name:`${e}_hla_typing_class_i_${t.name}`,modelValue:l(o)[`${e}_hla_note`][`hla_typing_class_i_${t.name}`],"onUpdate:modelValue":w=>l(o)[`${e}_hla_note`][`hla_typing_class_i_${t.name}`]=w},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),fe,be,a("div",Ve,[(_(!0),c(f,null,C(d.formConfigs.classIIAntigens.filter(t=>t.group===1),t=>(_(),b(u,{key:t.name,label:t.label,name:`${e}_hla_typing_class_ii_${t.name}`,modelValue:l(o)[`${e}_hla_note`][`hla_typing_class_ii_${t.name}`],"onUpdate:modelValue":w=>l(o)[`${e}_hla_note`][`hla_typing_class_ii_${t.name}`]=w},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),$e,s(u,{label:"hla mismatch",name:`${e}_hla_typing_mismatch`,modelValue:l(o)[`${e}_hla_note`].hla_typing_mismatch,"onUpdate:modelValue":t=>l(o)[`${e}_hla_note`].hla_typing_mismatch=t},null,8,["name","modelValue","onUpdate:modelValue"])],2))),128))],2)])):p("",!0)]),_:1}),s(B,{name:"slide-fade"},{default:$(()=>[r.request_cxm?(_(),c("div",xe,[Ce,a("div",{class:v(["mt-4 space-y-4 md:space-y-0 md:grid-cols-2 md:gap-4 xl:gap-6",{grid:r.donor_hn}])},[(_(!0),c(f,null,C(r.patients,e=>(_(),c("div",{class:v(["space-y-2 md:space-y-4",{"border-green-400 p-2 md:p-4 rounded-lg border-2":e==="patient"&&r.donor_hn,"border-amber-400 p-2 md:p-4 rounded-lg border-2":e==="donor"}]),key:e},[r.donor_hn?(_(),c("h4",ve,I(e==="patient"&&r.donor_hn?"recipient":e),1)):p("",!0),s(k,{label:"date test",name:`${e}_cxm_note.date_cross_matching`,modelValue:l(o)[`${e}_cxm_note`].date_cross_matching,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].date_cross_matching=t,error:m.$page.props.errors[`${e}_cxm_note.date_cross_matching`]},null,8,["name","modelValue","onUpdate:modelValue","error"]),Ue,a("div",we,[a("div",ke,[Ie,a("div",Oe,[a("div",Ae,[De,a("div",Ee,[s(h,{class:"w-1/2",label:"rt",name:`${e}_t_lymphocyte_cdc_neat_rt`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_neat_rt,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_neat_rt=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"]),s(h,{class:"w-1/2",label:"37℃",name:`${e}_t_lymphocyte_cdc_neat_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_neat_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_neat_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])])]),a("div",Se,[qe,a("div",Fe,[s(h,{class:"w-1/2",label:"rt",name:`${e}_t_lymphocyte_cdc_dtt_rt`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_dtt_rt,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_dtt_rt=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"]),s(h,{class:"w-1/2",label:"37℃",name:`${e}_t_lymphocyte_cdc_dtt_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_dtt_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_dtt_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])])])])]),a("div",Ne,[Te,a("div",Be,[a("div",Le,[Pe,s(h,{label:"rt",name:`${e}_t_lymphocyte_cdc_ahg_neat_rt`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_ahg_neat_rt,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_ahg_neat_rt=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])]),a("div",je,[He,s(h,{label:"rt",name:`${e}_t_lymphocyte_cdc_ahg_dtt_rt`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_ahg_dtt_rt,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_cdc_ahg_dtt_rt=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])])])])]),Re,ze,a("div",Ge,[a("div",Ye,[Ke,a("div",Me,[a("div",Je,[Qe,s(h,{label:"37℃",name:`${e}_b_lymphocyte_cdc_neat_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_neat_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_neat_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])]),a("div",We,[Xe,s(h,{label:"37℃",name:`${e}_b_lymphocyte_cdc_dtt_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_dtt_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_dtt_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])])])]),a("div",Ze,[eo,a("div",oo,[a("div",lo,[ao,s(h,{label:"37℃",name:`${e}_b_lymphocyte_cdc_ahg_neat_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_ahg_neat_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_ahg_neat_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])]),a("div",to,[so,s(h,{label:"37℃",name:`${e}_b_lymphocyte_cdc_ahg_dtt_37_degree_celsius`,options:d.formConfigs.lymphocyteCrossmatchOptions,modelValue:l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_ahg_dtt_37_degree_celsius,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].b_lymphocyte_cdc_ahg_dtt_37_degree_celsius=t,"allow-reset":!0,narrow:!0},null,8,["name","options","modelValue","onUpdate:modelValue"])])])])]),ro,no,s(u,{label:"t-lymphocyte",name:`${e}_t_lymphocyte_crossmatch`,modelValue:l(o)[`${e}_cxm_note`].t_lymphocyte_crossmatch,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].t_lymphocyte_crossmatch=t},null,8,["name","modelValue","onUpdate:modelValue"]),s(u,{label:"b-lymphocyte",name:`${e}_b_lymphocyte_crossmatch`,modelValue:l(o)[`${e}_cxm_note`].b_lymphocyte_crossmatch,"onUpdate:modelValue":t=>l(o)[`${e}_cxm_note`].b_lymphocyte_crossmatch=t},null,8,["name","modelValue","onUpdate:modelValue"])],2))),128))],2)])):p("",!0)]),_:1}),s(B,{name:"slide-fade"},{default:$(()=>[r.request_addition_tissue?(_(),c("div",mo,[_o,a("div",{class:v(["mt-4 gap-2 md:grid-cols-2 md:gap-4 xl:gap-6",{grid:r.donor_hn}])},[(_(!0),c(f,null,C(r.patients,e=>(_(),c("div",{key:e,class:v(["space-y-2 md:space-y-4",{"border-green-400 p-2 md:p-4 rounded-lg border-2":e==="patient"&&r.donor_hn,"border-amber-400 p-2 md:p-4 rounded-lg border-2":e==="donor"}])},[r.donor_hn?(_(),c("h4",co,I(r.donor_hn&&e==="patient"?"recipient":e),1)):p("",!0),s(k,{label:"date test",name:`${e}_date_addition_tissue_typing`,modelValue:l(o)[`${e}_addition_tissue_typing_note`].date_addition_tissue_typing,"onUpdate:modelValue":t=>l(o)[`${e}_addition_tissue_typing_note`].date_addition_tissue_typing=t,error:m.$page.props.errors[`${e}_addition_tissue_typing_note.date_addition_tissue_typing`]},null,8,["name","modelValue","onUpdate:modelValue","error"]),io,a("div",uo,[(_(!0),c(f,null,C(d.formConfigs.additionAntigens,t=>(_(),b(u,{key:t.name,label:t.label,name:`${e}_tissue_typing_${t.name}`,modelValue:l(o)[`${e}_addition_tissue_typing_note`][`tissue_typing_${t.name}`],"onUpdate:modelValue":w=>l(o)[`${e}_addition_tissue_typing_note`][`tissue_typing_${t.name}`]=w},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))])],2))),128))],2)])):p("",!0)]),_:1}),po,s(W,{label:"comments",name:"remark",modelValue:l(o).remark,"onUpdate:modelValue":n[12]||(n[12]=e=>l(o).remark=e)},null,8,["modelValue"]),ho,s(de,{label:"scanned report",pathname:d.formConfigs.upload_pathname,modelValue:l(o).scanned_report,"onUpdate:modelValue":n[13]||(n[13]=e=>l(o).scanned_report=e),"service-endpoints":d.formConfigs.routes.upload,error:m.$page.props.errors.scanned_report,name:"scanned_report"},null,8,["pathname","modelValue","service-endpoints","error"]),d.formConfigs.can.update?(_(),b(S,{key:0,spin:l(o).processing,onClick:g,class:"mt-4 md:mt-8 w-full btn-accent"},{default:$(()=>[D(" PUBLISH ")]),_:1},8,["spin"])):p("",!0),d.formConfigs.can.addendum?(_(),b(S,{key:1,spin:l(o).processing,onClick:V,class:"mt-4 md:mt-8 w-full btn-complement"},{default:$(()=>[D(" ADDENDUM ")]),_:1},8,["spin"])):p("",!0),d.formConfigs.can.destroy?(_(),b(S,{key:2,spin:l(o).processing,onClick:n[14]||(n[14]=e=>y("destroy-report")),class:"mt-4 md:mt-8 w-full btn-danger"},{default:$(()=>[D(" DELETE ")]),_:1},8,["spin"])):p("",!0),d.formConfigs.can.cancel?(_(),b(S,{key:3,spin:l(o).processing,onClick:n[15]||(n[15]=e=>y("cancel-report")),class:"mt-4 md:mt-8 w-full btn-warning"},{default:$(()=>[D(" CANCEL ")]),_:1},8,["spin"])):p("",!0),s(l(q),{ref_key:"confirmForm",ref:H,onConfirmed:n[16]||(n[16]=e=>l(R)(e,A))},null,512)],64))}};export{Io as default};
