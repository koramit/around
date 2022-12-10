import{_ as X,o as r,c as m,a as i,a5 as x,s as A,g as D,a6 as Y,C as $,r as S,f as Z,b as s,k as ee,d as C,i as _,w as u,T as f,u as e,F as v,a7 as w,q as h,ai as I,t as R,x as le,v as k,n as te,a8 as oe}from"./app.a2148d35.js";import{_ as U}from"./FormInput.8fdf7c5d.js";import{_ as c}from"./FormCheckbox.41c9c4e8.js";import{_ as ae}from"./FormSelect.c54afc9a.js";import{_ as N}from"./FormTextarea.0963278f.js";import{_ as T}from"./SpinnerButton.36acba4a.js";import{_ as se}from"./FormDatetime.e93fe170.js";import{_ as ne}from"./CommentSection.9583b36a.js";import{_ as ie}from"./SerologyInfo.864062db.js";import"./IconEyes.d3988583.js";const re={},de={viewBox:"0 0 512 512"},me=i("path",{fill:"currentColor",d:"M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96zM441.8 289.6c-16.94-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-37.96 0-73-14.18-100.2-37.8L200 334C216.9 317.2 205.2 288.6 181.6 288H34.66C24.32 288.3 16 296.7 16 307.1v145.9c0 24.04 29.07 36.08 46.07 19.07l48.5-48.53C149.5 458.8 200.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"},null,-1),ue=[me];function ce(p,y){return r(),m("svg",de,ue)}var _e=X(re,[["render",ce]]);const pe={class:"flex justify-between items-center",id:"reservation"},fe=i("span",{class:"form-label !mb-0 text-lg italic text-complement"},"Reservation data",-1),ye=i("hr",{class:"my-4 border-b border-accent"},null,-1),ge={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},be={key:0},he={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},Ve={class:"mt-2 lg:mt-0 md:pt-4"},xe={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},ve={class:"space-y-2 md:space-y-4 lg:space-y-6"},we={class:"form-label block"},Ce={key:0},ke={key:0},Ue={key:0},Ee={key:0},Pe=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"predialysis-evaluation"}," predialysis evaluation ",-1),Se=i("hr",{class:"my-4 border-b border-accent"},null,-1),Fe=i("label",{class:"form-label"},"hemodynamic :",-1),Oe={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},$e=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Re=i("label",{class:"form-label"},"Respiration :",-1),Te={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"},Le=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),qe=i("label",{class:"form-label"},"Oxygen support :",-1),Ae=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),De=i("label",{class:"form-label"},"Neurological evaluation :",-1),Ie={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-1 gap-4"},Ne=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Be=i("label",{class:"form-label"},"Life threatening condition in the past 24 hours :",-1),He={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},Me=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"monitoring"}," monitoring ",-1),ze=i("hr",{class:"my-4 border-b border-accent"},null,-1),We={key:0},je={class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-2 gap-4"},Qe=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"special-orders"}," special orders ",-1),Ge=i("hr",{class:"my-4 border-b border-accent"},null,-1),Je=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"discussion"}," discussion ",-1),Ke=i("hr",{class:"my-4 border-b border-accent"},null,-1),il={__name:"OrderEdit",props:{orderForm:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},setup(p){const y=p,B=x(()=>w(()=>import("./HDForm.faf69643.js"),["assets/HDForm.faf69643.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/FormCheckbox.41c9c4e8.js","assets/FormInput.8fdf7c5d.js","assets/FormSelect.c54afc9a.js","assets/FormSelectOther.82939972.js","assets/ModalDialog.0dba2dc7.js","assets/ModalDialog.7d4de9ea.css","assets/FormRadio.89245127.js","assets/IconCopy.956c7a53.js"])),H=x(()=>w(()=>import("./HFForm.3a79bb7c.js"),["assets/HFForm.3a79bb7c.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/FormCheckbox.41c9c4e8.js","assets/FormInput.8fdf7c5d.js","assets/FormSelect.c54afc9a.js","assets/FormRadio.89245127.js","assets/FormSelectOther.82939972.js","assets/ModalDialog.0dba2dc7.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.956c7a53.js"])),M=x(()=>w(()=>import("./SLEDDForm.2089b1d8.js"),["assets/SLEDDForm.2089b1d8.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/FormCheckbox.41c9c4e8.js","assets/FormInput.8fdf7c5d.js","assets/FormTextarea.0963278f.js","assets/FormSelect.c54afc9a.js","assets/FormRadio.89245127.js","assets/FormSelectOther.82939972.js","assets/ModalDialog.0dba2dc7.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.956c7a53.js"])),z=x(()=>w(()=>import("./TPEForm.19d52a40.js"),["assets/TPEForm.19d52a40.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/FormCheckbox.41c9c4e8.js","assets/FormInput.8fdf7c5d.js","assets/FormSelect.c54afc9a.js","assets/FormRadio.89245127.js","assets/FormSelectOther.82939972.js","assets/ModalDialog.0dba2dc7.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.956c7a53.js"])),W=x(()=>w(()=>import("./DialysisSlot.27703299.js"),["assets/DialysisSlot.27703299.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/WardSlot.360fbfdb.js","assets/IconUserMd.cbbc3213.js"])),j=x(()=>w(()=>import("./WardSlot.360fbfdb.js").then(function(n){return n.W}),["assets/WardSlot.360fbfdb.js","assets/app.a2148d35.js","assets/app.43783091.css","assets/IconUserMd.cbbc3213.js"])),a=A({...y.formConfigs}),t=D({...y.orderForm}),{autosave:Q}=Y();$(()=>t.data(),n=>{n.hemodynamic.stable&&(n.hemodynamic.hypotension=!1,n.hemodynamic.inotropic_dependent=!1,n.hemodynamic.severe_hypertension=!1,n.hemodynamic.bradycardia=!1,n.hemodynamic.arrhythmia=!1),n.respiration.stable&&(n.respiration.hypoxia=!1,n.respiration.high_risk_airway_obstruction=!1),n.neurological.stable&&(n.neurological.gcs_drop=!1,n.neurological.drowsiness=!1),n.life_threatening_condition.stable&&(n.life_threatening_condition.acute_coronary_syndrome=!1,n.life_threatening_condition.cardiac_arrhythmia_with_hypotension=!1,n.life_threatening_condition.acute_ischemic_stroke=!1,n.life_threatening_condition.acute_ich=!1,n.life_threatening_condition.seizure=!1,n.life_threatening_condition.cardiac_arrest=!1),n.monitor.standard&&(n.monitor.ekg=!1,n.monitor.observe_chest_pain=!1,n.monitor.observe_neuro_sign=!1,n.monitor.other=null),Q(t.data(),a.routes.update)},{deep:a.can.update});const L=()=>t.patch(a.routes.submit),{actionStore:G}=oe();$(()=>G.value,n=>{switch(n.name){case"submit":L();break;default:return}},{deep:!0});const E=S(!1),d=D({dialysis_type:a.dialysis_type,dialysis_at:a.dialysis_at,attending_staff:null,date_note:a.date_note,patient_type:null,swap_with:null}),J=S(null),g=A({slots:[],available:!1,reply:""}),q=()=>window.axios.post(a.routes.acute_hemodialysis_slot_available,{dialysis_type:d.dialysis_type,dialysis_at:d.dialysis_at,date_note:d.date_note}).then(n=>{g.slots=n.data.slots,g.available=n.data.available,g.reply=n.data.reply});Z(q),$(()=>d.date_note,n=>{!n||q()});const F=()=>{a.reserve_available_dates=y.formConfigs.reserve_available_dates,a.date_note=y.formConfigs.date_note,a.dialysis_type=y.formConfigs.dialysis_type,a.dialysis_at=y.formConfigs.dialysis_at,a.swap_code=y.formConfigs.swap_code,a.can=y.formConfigs.can},V=S(!1),O=S(!1),P=()=>{window.axios.patch(a.routes.copy).then(n=>{if(!n.data.found){O.value=!0,setTimeout(()=>O.value=!1,2e3);return}V.value=!0,te(()=>{n.data.records.map(o=>{t[o.type]=o.form})}),setTimeout(()=>V.value=!1,200)})};return(n,o)=>(r(),m(v,null,[s(ie,{class:"mb-4 md:mb-8",serology:p.formConfigs.serology},null,8,["serology"]),i("h2",pe,[fe,a.can.reschedule?(r(),m("button",{key:0,class:"flex items-center text-sm text-accent",onClick:o[0]||(o[0]=l=>E.value=!E.value)},[s(_e,{class:ee(["w-3 h-3 mr-1 transition-all transform duration-200 ease-out",{"rotate-180 text-accent-darker":E.value}])},null,8,["class"]),C(" Reschedule ")])):_("",!0)]),ye,i("div",ge,[s(U,{modelValue:a.hn,"onUpdate:modelValue":o[1]||(o[1]=l=>a.hn=l),name:"hn",label:"hn",readonly:!0},null,8,["modelValue"]),s(U,{modelValue:a.an,"onUpdate:modelValue":o[2]||(o[2]=l=>a.an=l),name:"an",label:"an",placeholder:"No active admission",readonly:!0},null,8,["modelValue"]),s(U,{modelValue:a.dialysis_at,"onUpdate:modelValue":o[3]||(o[3]=l=>a.dialysis_at=l),name:"dialysis_at",label:"dialysis at",readonly:!0},null,8,["modelValue"]),s(U,{modelValue:a.dialysis_type,"onUpdate:modelValue":o[4]||(o[4]=l=>a.dialysis_type=l),name:"dialysis_type",label:"dialysis type",readonly:!0},null,8,["modelValue"])]),s(f,{name:"slide-fade"},{default:u(()=>[E.value&&a.can.reschedule?(r(),m("div",be,[i("div",he,[s(se,{label:"reschedule date",name:"date_note",modelValue:e(d).date_note,"onUpdate:modelValue":o[5]||(o[5]=l=>e(d).date_note=l),options:{enable:a.reserve_available_dates,inline:!0},ref_key:"dateNoteInput",ref:J},null,8,["modelValue","options"]),i("div",null,[e(d).dialysis_at.indexOf("Hemo")!==-1?(r(),h(e(W),{key:0,slots:g.slots},null,8,["slots"])):(r(),h(e(j),{key:1,slots:g.slots},null,8,["slots"])),g.reply&&!g.available?(r(),h(I,{key:2,class:"mt-4",type:"warning",title:"Cannot make a reservation",message:g.reply},null,8,["message"])):_("",!0)]),i("div",Ve,[s(T,{class:"block w-full text-center btn btn-accent",spin:e(d).processing,disabled:e(d).date_note===a.date_note||!g.available,onClick:o[6]||(o[6]=l=>e(d).date_note!==a.today?e(d).patch(a.routes.reschedule,{onFinish:F}):e(d).patch(a.routes.today_slot_request,{onFinish:F}))},{default:u(()=>[C(R(e(d).date_note===a.today||a.date_note===a.today?"REQUEST RESCHEDULE":"RESCHEDULE"),1)]),_:1},8,["spin","disabled"])])]),i("div",xe,[i("div",ve,[i("label",we,[C(" swap code : "),s(le,{text:a.swap_code},{default:u(()=>[i("span",null,R(a.swap_code),1)]),_:1},8,["text"])]),s(U,{modelValue:e(d).swap_with,"onUpdate:modelValue":o[7]||(o[7]=l=>e(d).swap_with=l),name:"swap_with",label:"swap with"},null,8,["modelValue"]),s(T,{class:"block w-full text-center btn btn-accent",spin:e(d).processing,disabled:!e(d).swap_with||e(d).swap_with===a.swap_code,onClick:o[8]||(o[8]=l=>e(d).patch(a.routes.swap,{onFinish:F}))},{default:u(()=>[C(R(e(d).swap_with!==a.swap_code?"SWAP":"\u{1F644}\u{1F644}\u{1F644}"),1)]),_:1},8,["spin","disabled"])])])])):_("",!0)]),_:1}),s(f,{name:"slide-fade"},{default:u(()=>[O.value?(r(),h(I,{key:0,class:"mt-4 md:mt-8",message:"No previous order",title:"\u{1F605}",type:"warning"})):_("",!0)]),_:1}),s(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.hd!==void 0&&!V.value?(r(),m("div",Ce,[s(e(B),{modelValue:e(t).hd,"onUpdate:modelValue":o[9]||(o[9]=l=>e(t).hd=l),"form-configs":p.formConfigs,onCopyPreviousOrder:P},null,8,["modelValue","form-configs"])])):_("",!0)]),_:1}),s(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.hf!==void 0&&!V.value?(r(),m("div",ke,[s(e(H),{modelValue:e(t).hf,"onUpdate:modelValue":o[10]||(o[10]=l=>e(t).hf=l),"form-configs":p.formConfigs,onCopyPreviousOrder:P},null,8,["modelValue","form-configs"])])):_("",!0)]),_:1}),s(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.sledd!==void 0&&!V.value?(r(),m("div",Ue,[s(e(M),{modelValue:e(t).sledd,"onUpdate:modelValue":o[11]||(o[11]=l=>e(t).sledd=l),"form-configs":p.formConfigs,onCopyPreviousOrder:P},null,8,["modelValue","form-configs"])])):_("",!0)]),_:1}),s(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.tpe!==void 0&&!V.value?(r(),m("div",Ee,[s(e(z),{modelValue:e(t).tpe,"onUpdate:modelValue":o[12]||(o[12]=l=>e(t).tpe=l),"form-configs":p.formConfigs,onCopyPreviousOrder:P},null,8,["modelValue","form-configs"])])):_("",!0)]),_:1}),Pe,Se,Fe,s(c,{name:"hemodynamic.stable",modelValue:e(t).hemodynamic.stable,"onUpdate:modelValue":o[13]||(o[13]=l=>e(t).hemodynamic.stable=l),label:"Stable",toggler:!0,error:e(t).errors["hemodynamic.stable"]},null,8,["modelValue","error"]),s(f,{name:"slide-fade"},{default:u(()=>[e(t).hemodynamic.stable?_("",!0):(r(),m("div",Oe,[(r(!0),m(v,null,k(a.hemodynamic_symptoms,l=>(r(),h(c,{key:l.name,name:"hypotension",modelValue:e(t).hemodynamic[l.name],"onUpdate:modelValue":b=>e(t).hemodynamic[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),$e,Re,s(c,{name:"respiration.stable",modelValue:e(t).respiration.stable,"onUpdate:modelValue":o[14]||(o[14]=l=>e(t).respiration.stable=l),label:"Stable",toggler:!0,error:e(t).errors["respiration.stable"]},null,8,["modelValue","error"]),s(f,{name:"slide-fade"},{default:u(()=>[e(t).respiration.stable?_("",!0):(r(),m("div",Te,[(r(!0),m(v,null,k(a.respiration_options,l=>(r(),h(c,{key:l.name,name:"hypotension",modelValue:e(t).respiration[l.name],"onUpdate:modelValue":b=>e(t).respiration[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Le,qe,s(ae,{modelValue:e(t).oxygen_support,"onUpdate:modelValue":o[15]||(o[15]=l=>e(t).oxygen_support=l),name:"oxygen_support",options:a.oxygen_options},null,8,["modelValue","options"]),Ae,De,s(c,{name:"neurological.stable",modelValue:e(t).neurological.stable,"onUpdate:modelValue":o[16]||(o[16]=l=>e(t).neurological.stable=l),label:"Stable",toggler:!0,error:e(t).errors["neurological.stable"]},null,8,["modelValue","error"]),s(f,{name:"slide-fade"},{default:u(()=>[e(t).neurological.stable?_("",!0):(r(),m("div",Ie,[(r(!0),m(v,null,k(a.neurological_options,l=>(r(),h(c,{key:l.name,name:"hypotension",modelValue:e(t).neurological[l.name],"onUpdate:modelValue":b=>e(t).neurological[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Ne,Be,s(c,{name:"life_threatening_condition.stable",modelValue:e(t).life_threatening_condition.stable,"onUpdate:modelValue":o[17]||(o[17]=l=>e(t).life_threatening_condition.stable=l),label:"Stable",toggler:!0,error:e(t).errors["life_threatening_condition.stable"]},null,8,["modelValue","error"]),s(f,{name:"slide-fade"},{default:u(()=>[e(t).life_threatening_condition.stable?_("",!0):(r(),m("div",He,[(r(!0),m(v,null,k(a.life_threatening_condition_options,l=>(r(),h(c,{key:l.name,name:"hypotension",modelValue:e(t).life_threatening_condition[l.name],"onUpdate:modelValue":b=>e(t).life_threatening_condition[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Me,ze,s(c,{name:"monitor.standard",modelValue:e(t).monitor.standard,"onUpdate:modelValue":o[18]||(o[18]=l=>e(t).monitor.standard=l),label:"Standard (MAP \u2265 65 mmHg)",toggler:!0,error:e(t).errors["monitor.standard"]},null,8,["modelValue","error"]),s(f,{name:"slide-fade"},{default:u(()=>[e(t).monitor.standard?_("",!0):(r(),m("div",We,[i("div",je,[(r(!0),m(v,null,k(a.monitors,(l,b)=>(r(),h(c,{key:b,label:l.label,name:l.name,modelValue:e(t).monitor[l.name],"onUpdate:modelValue":K=>e(t).monitor[l.name]=K},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),s(N,{class:"mt-2 md:mt-4 xl:mt-8",label:"other",placeholder:"others...",name:"monitoring_other",modelValue:e(t).monitor.other,"onUpdate:modelValue":o[19]||(o[19]=l=>e(t).monitor.other=l)},null,8,["modelValue"])]))]),_:1}),Qe,Ge,s(c,{class:"mt-2 md:bt-4 xl:mt-8",name:"predialysis_labs_request",modelValue:e(t).predialysis_labs_request,"onUpdate:modelValue":o[20]||(o[20]=l=>e(t).predialysis_labs_request=l),label:"Predialysis Labs request",toggler:!0},null,8,["modelValue"]),s(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_bw",modelValue:e(t).postdialysis_bw,"onUpdate:modelValue":o[21]||(o[21]=l=>e(t).postdialysis_bw=l),label:"Postdialysis BW",toggler:!0},null,8,["modelValue"]),s(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_esa",modelValue:e(t).postdialysis_esa,"onUpdate:modelValue":o[22]||(o[22]=l=>e(t).postdialysis_esa=l),label:"Postdialysis ESA",toggler:!0},null,8,["modelValue"]),s(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_iron_iv",modelValue:e(t).postdialysis_iron_iv,"onUpdate:modelValue":o[23]||(o[23]=l=>e(t).postdialysis_iron_iv=l),label:"Postdialysis Iron IV",toggler:!0},null,8,["modelValue"]),s(N,{class:"mt-2 md:mt-4 xl:mt-8",label:"treatments request",name:"treatments_request",modelValue:e(t).treatments_request,"onUpdate:modelValue":o[24]||(o[24]=l=>e(t).treatments_request=l),error:e(t).errors.treatments_request},null,8,["modelValue","error"]),s(T,{onClick:L,spin:e(t).processing,class:"mt-4 md:mt-8 w-full btn-accent"},{default:u(()=>[C(" SUBMIT ")]),_:1},8,["spin"]),Je,Ke,s(ne,{configs:a.comment},null,8,["configs"])],64))}};export{il as default};
