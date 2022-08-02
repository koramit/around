import{_ as G,o as d,c as u,b as i,H as h,B as D,h as T,C as F,r as q,g as J,d as n,l as Q,k as c,w as f,T as V,s as _,u as e,F as x,e as U,J as w,ac as K,t as S,ad as X,v as k,G as $,n as Y}from"./app.7955817d.js";import{d as Z}from"./debounce.dcd03233.js";import{_ as C}from"./FormInput.0e67a484.js";import{_ as m}from"./FormCheckbox.0c7e771d.js";import{_ as ee}from"./FormSelect.9d3cda6f.js";import{_ as I}from"./FormTextarea.9471a26c.js";import{_ as P}from"./SpinnerButton.1cb5e220.js";import{_ as le}from"./FormDatetime.989f5f67.js";import{_ as te}from"./CommentSection.d304a1bf.js";const oe={},ae={viewBox:"0 0 512 512"},se=i("path",{fill:"currentColor",d:"M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96zM441.8 289.6c-16.94-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-37.96 0-73-14.18-100.2-37.8L200 334C216.9 317.2 205.2 288.6 181.6 288H34.66C24.32 288.3 16 296.7 16 307.1v145.9c0 24.04 29.07 36.08 46.07 19.07l48.5-48.53C149.5 458.8 200.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"},null,-1),ne=[se];function ie(p,y){return d(),u("svg",ae,ne)}var de=G(oe,[["render",ie]]);const re={class:"flex justify-between items-center",id:"reservation"},me=i("span",{class:"form-label !mb-0 text-lg italic text-complement"},"Reservation data",-1),ue=U(" Reschedule "),ce=i("hr",{class:"my-4 border-b border-accent"},null,-1),_e={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},pe={key:0},fe={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},ye={class:"mt-2 lg:mt-0 md:pt-4"},ge={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},be={class:"space-y-2 md:space-y-4 lg:space-y-6"},he={class:"form-label block"},Ve=U(" swap code : "),xe=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"predialysis-evaluation"}," predialysis evaluation ",-1),we=i("hr",{class:"my-4 border-b border-accent"},null,-1),ve=i("label",{class:"form-label"},"hemodynamic :",-1),ke={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},Ce=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Ue=i("label",{class:"form-label"},"Respiration :",-1),Ee={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"},Ae=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Fe=i("label",{class:"form-label"},"Oxygen support :",-1),Se=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),$e=i("label",{class:"form-label"},"Neurological evaluation :",-1),Pe={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-1 gap-4"},Re=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Le=i("label",{class:"form-label"},"Life threatening condition in the past 24 hours :",-1),De={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},Te=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"monitoring"}," monitoring ",-1),qe=i("hr",{class:"my-4 border-b border-accent"},null,-1),Ie={key:0},Oe={class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-2 gap-4"},Be=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"special-orders"}," special orders ",-1),He=i("hr",{class:"my-4 border-b border-accent"},null,-1),Ne=U(" SUBMIT "),Me=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"discussion"}," discussion ",-1),ze=i("hr",{class:"my-4 border-b border-accent"},null,-1),el={__name:"OrderEdit",props:{orderForm:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},setup(p){const y=p,O=h(()=>w(()=>import("./HDForm.5e5aafde.js"),["assets/HDForm.5e5aafde.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/FormCheckbox.0c7e771d.js","assets/FormInput.0e67a484.js","assets/FormSelect.9d3cda6f.js","assets/FormSelectOther.06e0e87e.js","assets/ModalDialog.45b0c964.js","assets/ModalDialog.db9c9a7c.css","assets/FormRadio.d29dff2e.js"])),B=h(()=>w(()=>import("./HFForm.c4dab1c3.js"),["assets/HFForm.c4dab1c3.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/FormCheckbox.0c7e771d.js","assets/FormInput.0e67a484.js","assets/FormSelect.9d3cda6f.js","assets/FormRadio.d29dff2e.js","assets/FormSelectOther.06e0e87e.js","assets/ModalDialog.45b0c964.js","assets/ModalDialog.db9c9a7c.css"])),H=h(()=>w(()=>import("./SLEDDForm.20b251b4.js"),["assets/SLEDDForm.20b251b4.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/FormCheckbox.0c7e771d.js","assets/FormInput.0e67a484.js","assets/FormTextarea.9471a26c.js","assets/debounce.dcd03233.js","assets/FormSelect.9d3cda6f.js","assets/FormRadio.d29dff2e.js","assets/FormSelectOther.06e0e87e.js","assets/ModalDialog.45b0c964.js","assets/ModalDialog.db9c9a7c.css"])),N=h(()=>w(()=>import("./TPEForm.2718d200.js"),["assets/TPEForm.2718d200.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/FormCheckbox.0c7e771d.js","assets/FormInput.0e67a484.js","assets/FormSelect.9d3cda6f.js","assets/FormRadio.d29dff2e.js","assets/FormSelectOther.06e0e87e.js","assets/ModalDialog.45b0c964.js","assets/ModalDialog.db9c9a7c.css"])),M=h(()=>w(()=>import("./DialysisSlot.83ea99a5.js"),["assets/DialysisSlot.83ea99a5.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/WardSlot.c174b5bb.js","assets/IconUserMd.fd030574.js"])),z=h(()=>w(()=>import("./WardSlot.c174b5bb.js").then(function(s){return s.W}),["assets/WardSlot.c174b5bb.js","assets/app.7955817d.js","assets/app.7bdba1f1.css","assets/IconUserMd.fd030574.js"])),a=D({...y.formConfigs}),t=T({...y.orderForm});F(()=>t,s=>{s.hemodynamic.stable&&(s.hemodynamic.hypotension=!1,s.hemodynamic.inotropic_dependent=!1,s.hemodynamic.severe_hypertension=!1,s.hemodynamic.bradycardia=!1,s.hemodynamic.arrhythmia=!1),s.respiration.stable&&(s.respiration.hypoxia=!1,s.respiration.high_risk_airway_obstruction=!1),s.neurological.stable&&(s.neurological.gcs_drop=!1,s.neurological.drowsiness=!1),s.life_threatening_condition.stable&&(s.life_threatening_condition.acute_coronary_syndrome=!1,s.life_threatening_condition.cardiac_arrhythmia_with_hypotension=!1,s.life_threatening_condition.acute_ischemic_stroke=!1,s.life_threatening_condition.acute_ich=!1,s.life_threatening_condition.seizure=!1,s.life_threatening_condition.cardiac_arrest=!1),s.monitor.standard&&(s.monitor.ekg=!1,s.monitor.observe_chest_pain=!1,s.monitor.observe_neuro_sign=!1,s.monitor.other=null),v()},{deep:!0});const v=Z(function(){!a.can.update||window.axios.patch(a.endpoints.update,t.data()).catch(s=>{console.log(s)})},3e3),R=()=>t.patch(a.endpoints.submit);F(()=>$().props.value.event.fire,s=>{!s||$().props.value.event.name==="action-clicked"&&$().props.value.event.payload==="submit"&&Y(R)});const E=q(!1),r=T({dialysis_type:a.dialysis_type,dialysis_at:a.dialysis_at,attending_staff:null,date_note:a.date_note,patient_type:null,swap_with:null}),W=q(null),g=D({slots:[],available:!1,reply:""}),L=()=>window.axios.post(a.endpoints.acute_hemodialysis_slot_available,{dialysis_type:r.dialysis_type,dialysis_at:r.dialysis_at,date_note:r.date_note}).then(s=>{g.slots=s.data.slots,g.available=s.data.available,g.reply=s.data.reply});J(L),F(()=>r.date_note,s=>{!s||L()});const A=()=>{a.reserve_available_dates=y.formConfigs.reserve_available_dates,a.date_note=y.formConfigs.date_note,a.dialysis_type=y.formConfigs.dialysis_type,a.dialysis_at=y.formConfigs.dialysis_at,a.swap_code=y.formConfigs.swap_code,a.can=y.formConfigs.can};return(s,o)=>(d(),u(x,null,[i("h2",re,[me,a.can.reschedule?(d(),u("button",{key:0,class:"flex items-center text-sm text-accent",onClick:o[0]||(o[0]=l=>E.value=!E.value)},[n(de,{class:Q(["w-3 h-3 mr-1 transition-all transform duration-200 ease-out",{"rotate-180 text-accent-darker":E.value}])},null,8,["class"]),ue])):c("",!0)]),ce,i("div",_e,[n(C,{modelValue:a.hn,"onUpdate:modelValue":o[1]||(o[1]=l=>a.hn=l),name:"hn",label:"hn",readonly:!0},null,8,["modelValue"]),n(C,{modelValue:a.an,"onUpdate:modelValue":o[2]||(o[2]=l=>a.an=l),name:"an",label:"an",placeholder:"No active admission",readonly:!0},null,8,["modelValue"]),n(C,{modelValue:a.dialysis_at,"onUpdate:modelValue":o[3]||(o[3]=l=>a.dialysis_at=l),name:"dialysis_at",label:"dialysis at",readonly:!0},null,8,["modelValue"]),n(C,{modelValue:a.dialysis_type,"onUpdate:modelValue":o[4]||(o[4]=l=>a.dialysis_type=l),name:"dialysis_type",label:"dialysis type",readonly:!0},null,8,["modelValue"])]),n(V,{name:"slide-fade"},{default:f(()=>[E.value&&a.can.reschedule?(d(),u("div",pe,[i("div",fe,[n(le,{label:"reschedule date",name:"date_note",modelValue:e(r).date_note,"onUpdate:modelValue":o[5]||(o[5]=l=>e(r).date_note=l),options:{enable:a.reserve_available_dates,inline:!0},ref_key:"dateNoteInput",ref:W},null,8,["modelValue","options"]),i("div",null,[e(r).dialysis_at.indexOf("Hemo")!==-1?(d(),_(e(M),{key:0,slots:g.slots},null,8,["slots"])):(d(),_(e(z),{key:1,slots:g.slots},null,8,["slots"])),g.reply&&!g.available?(d(),_(K,{key:2,class:"mt-4",type:"warning",title:"Cannot make a reservation",message:g.reply},null,8,["message"])):c("",!0)]),i("div",ye,[n(P,{class:"block w-full text-center btn btn-accent",spin:e(r).processing,disabled:e(r).date_note===a.date_note||!g.available,onClick:o[6]||(o[6]=l=>e(r).date_note!==a.today?e(r).patch(a.endpoints.reschedule,{onFinish:A}):e(r).patch(a.endpoints.today_slot_request,{onFinish:A}))},{default:f(()=>[U(S(e(r).date_note===a.today||a.date_note===a.today?"REQUEST RESCHEDULE":"RESCHEDULE"),1)]),_:1},8,["spin","disabled"])])]),i("div",ge,[i("div",be,[i("label",he,[Ve,n(X,{text:a.swap_code},{default:f(()=>[i("span",null,S(a.swap_code),1)]),_:1},8,["text"])]),n(C,{modelValue:e(r).swap_with,"onUpdate:modelValue":o[7]||(o[7]=l=>e(r).swap_with=l),name:"swap_with",label:"swap with"},null,8,["modelValue"]),n(P,{class:"block w-full text-center btn btn-accent",spin:e(r).processing,disabled:!e(r).swap_with||e(r).swap_with===a.swap_code,onClick:o[8]||(o[8]=l=>e(r).patch(a.endpoints.swap,{onFinish:A}))},{default:f(()=>[U(S(e(r).swap_with!==a.swap_code?"SWAP":"\u{1F644}\u{1F644}\u{1F644}"),1)]),_:1},8,["spin","disabled"])])])])):c("",!0)]),_:1}),p.orderForm.hd!==void 0?(d(),_(e(O),{key:0,modelValue:e(t).hd,"onUpdate:modelValue":o[9]||(o[9]=l=>e(t).hd=l),"form-configs":p.formConfigs,onAutosave:e(v)},null,8,["modelValue","form-configs","onAutosave"])):c("",!0),p.orderForm.hf!==void 0?(d(),_(e(B),{key:1,modelValue:e(t).hf,"onUpdate:modelValue":o[10]||(o[10]=l=>e(t).hf=l),"form-configs":p.formConfigs,onAutosave:e(v)},null,8,["modelValue","form-configs","onAutosave"])):c("",!0),p.orderForm.sledd!==void 0?(d(),_(e(H),{key:2,modelValue:e(t).sledd,"onUpdate:modelValue":o[11]||(o[11]=l=>e(t).sledd=l),"form-configs":p.formConfigs,onAutosave:e(v)},null,8,["modelValue","form-configs","onAutosave"])):c("",!0),p.orderForm.tpe!==void 0?(d(),_(e(N),{key:3,modelValue:e(t).tpe,"onUpdate:modelValue":o[12]||(o[12]=l=>e(t).tpe=l),"form-configs":p.formConfigs,onAutosave:e(v)},null,8,["modelValue","form-configs","onAutosave"])):c("",!0),xe,we,ve,n(m,{name:"hemodynamic.stable",modelValue:e(t).hemodynamic.stable,"onUpdate:modelValue":o[13]||(o[13]=l=>e(t).hemodynamic.stable=l),label:"Stable",toggler:!0,error:e(t).errors["hemodynamic.stable"]},null,8,["modelValue","error"]),n(V,{name:"slide-fade"},{default:f(()=>[e(t).hemodynamic.stable?c("",!0):(d(),u("div",ke,[(d(!0),u(x,null,k(a.hemodynamic_symptoms,l=>(d(),_(m,{key:l.name,name:"hypotension",modelValue:e(t).hemodynamic[l.name],"onUpdate:modelValue":b=>e(t).hemodynamic[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Ce,Ue,n(m,{name:"respiration.stable",modelValue:e(t).respiration.stable,"onUpdate:modelValue":o[14]||(o[14]=l=>e(t).respiration.stable=l),label:"Stable",toggler:!0,error:e(t).errors["respiration.stable"]},null,8,["modelValue","error"]),n(V,{name:"slide-fade"},{default:f(()=>[e(t).respiration.stable?c("",!0):(d(),u("div",Ee,[(d(!0),u(x,null,k(a.raspiration_options,l=>(d(),_(m,{key:l.name,name:"hypotension",modelValue:e(t).respiration[l.name],"onUpdate:modelValue":b=>e(t).respiration[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Ae,Fe,n(ee,{modelValue:e(t).oxygen_support,"onUpdate:modelValue":o[15]||(o[15]=l=>e(t).oxygen_support=l),name:"oxygen_support",options:a.oxygen_options},null,8,["modelValue","options"]),Se,$e,n(m,{name:"neurological.stable",modelValue:e(t).neurological.stable,"onUpdate:modelValue":o[16]||(o[16]=l=>e(t).neurological.stable=l),label:"Stable",toggler:!0,error:e(t).errors["neurological.stable"]},null,8,["modelValue","error"]),n(V,{name:"slide-fade"},{default:f(()=>[e(t).neurological.stable?c("",!0):(d(),u("div",Pe,[(d(!0),u(x,null,k(a.neurological_options,l=>(d(),_(m,{key:l.name,name:"hypotension",modelValue:e(t).neurological[l.name],"onUpdate:modelValue":b=>e(t).neurological[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Re,Le,n(m,{name:"life_threatening_condition.stable",modelValue:e(t).life_threatening_condition.stable,"onUpdate:modelValue":o[17]||(o[17]=l=>e(t).life_threatening_condition.stable=l),label:"Stable",toggler:!0,error:e(t).errors["life_threatening_condition.stable"]},null,8,["modelValue","error"]),n(V,{name:"slide-fade"},{default:f(()=>[e(t).life_threatening_condition.stable?c("",!0):(d(),u("div",De,[(d(!0),u(x,null,k(a.life_threatening_condition_options,l=>(d(),_(m,{key:l.name,name:"hypotension",modelValue:e(t).life_threatening_condition[l.name],"onUpdate:modelValue":b=>e(t).life_threatening_condition[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Te,qe,n(m,{name:"monitor.standard",modelValue:e(t).monitor.standard,"onUpdate:modelValue":o[18]||(o[18]=l=>e(t).monitor.standard=l),label:"Standard (MAP \u2265 65 mmHg)",toggler:!0,error:e(t).errors["monitor.standard"]},null,8,["modelValue","error"]),n(V,{name:"slide-fade"},{default:f(()=>[e(t).monitor.standard?c("",!0):(d(),u("div",Ie,[i("div",Oe,[(d(!0),u(x,null,k(a.monitors,(l,b)=>(d(),_(m,{key:b,label:l.label,name:l.name,modelValue:e(t).monitor[l.name],"onUpdate:modelValue":j=>e(t).monitor[l.name]=j},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),n(I,{class:"mt-2 md:mt-4 xl:mt-8",label:"other",placeholder:"others...",name:"monitoring_other",modelValue:e(t).monitor.other,"onUpdate:modelValue":o[19]||(o[19]=l=>e(t).monitor.other=l)},null,8,["modelValue"])]))]),_:1}),Be,He,n(m,{class:"mt-2 md:bt-4 xl:mt-8",name:"predialysis_labs_request",modelValue:e(t).predialysis_labs_request,"onUpdate:modelValue":o[20]||(o[20]=l=>e(t).predialysis_labs_request=l),label:"Predialysis Labs request",toggler:!0},null,8,["modelValue"]),n(m,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_bw",modelValue:e(t).postdialysis_bw,"onUpdate:modelValue":o[21]||(o[21]=l=>e(t).postdialysis_bw=l),label:"Postdialysis BW",toggler:!0},null,8,["modelValue"]),n(m,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_esa",modelValue:e(t).postdialysis_esa,"onUpdate:modelValue":o[22]||(o[22]=l=>e(t).postdialysis_esa=l),label:"Postdialysis ESA",toggler:!0},null,8,["modelValue"]),n(m,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_iron_iv",modelValue:e(t).postdialysis_iron_iv,"onUpdate:modelValue":o[23]||(o[23]=l=>e(t).postdialysis_iron_iv=l),label:"Postdialysis Iron IV",toggler:!0},null,8,["modelValue"]),n(I,{class:"mt-2 md:mt-4 xl:mt-8",label:"treatments request",name:"treatments_request",modelValue:e(t).treatments_request,"onUpdate:modelValue":o[24]||(o[24]=l=>e(t).treatments_request=l),error:e(t).errors.treatments_request},null,8,["modelValue","error"]),n(P,{onClick:R,spin:e(t).processing,class:"mt-4 md:mt-8 w-full btn-accent"},{default:f(()=>[Ne]),_:1},8,["spin"]),Me,ze,n(te,{configs:a.comment},null,8,["configs"])],64))}};export{el as default};