import{_ as Y,o as d,c as m,a as i,ad as v,s as I,g as N,E as $,r as S,f as Z,b as n,k as ee,i as _,w as u,T as f,u as e,F as x,d as E,ae as w,q as h,af as B,t as R,x as le,v as k,a4 as T,n as H}from"./app.94c97313.js";import{d as oe}from"./debounce.72e45d68.js";import{_ as U}from"./FormInput.b47cd78a.js";import{_ as c}from"./FormCheckbox.5b8c2adf.js";import{_ as te}from"./FormSelect.ebdade6a.js";import{_ as M}from"./FormTextarea.fded3dbe.js";import{_ as L}from"./SpinnerButton.22451709.js";import{_ as ae}from"./FormDatetime.534bf4f1.js";import{_ as se}from"./CommentSection.577e3ef3.js";import{_ as ne}from"./SerologyInfo.fa90f709.js";const ie={},de={viewBox:"0 0 512 512"},re=i("path",{fill:"currentColor",d:"M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96zM441.8 289.6c-16.94-5.438-34.88 3.812-40.3 20.59C381.1 373.5 322.6 416 256 416c-37.96 0-73-14.18-100.2-37.8L200 334C216.9 317.2 205.2 288.6 181.6 288H34.66C24.32 288.3 16 296.7 16 307.1v145.9c0 24.04 29.07 36.08 46.07 19.07l48.5-48.53C149.5 458.8 200.6 480 255.1 480c94.45 0 177.4-60.34 206.4-150.2C467.9 313 458.6 294.1 441.8 289.6z"},null,-1),me=[re];function ue(p,y){return d(),m("svg",de,me)}var ce=Y(ie,[["render",ue]]);const _e={class:"flex justify-between items-center",id:"reservation"},pe=i("span",{class:"form-label !mb-0 text-lg italic text-complement"},"Reservation data",-1),fe=E(" Reschedule "),ye=i("hr",{class:"my-4 border-b border-accent"},null,-1),ge={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},be={key:0},he={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},Ve={class:"mt-2 lg:mt-0 md:pt-4"},ve={class:"mt-4 md:mt-8 xl:mt-16 grid xl:grid-cols-2 gap-2 md:gap-4 lg:gap-6"},xe={class:"space-y-2 md:space-y-4 lg:space-y-6"},we={class:"form-label block"},Ce=E(" swap code : "),ke={key:0},Ue={key:0},Ee={key:0},Pe={key:0},Ae=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"predialysis-evaluation"}," predialysis evaluation ",-1),Se=i("hr",{class:"my-4 border-b border-accent"},null,-1),Fe=i("label",{class:"form-label"},"hemodynamic :",-1),Oe={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},$e=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Re=i("label",{class:"form-label"},"Respiration :",-1),Te={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 xl:space-y-4"},Le=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),qe=i("label",{class:"form-label"},"Oxygen support :",-1),De=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),Ie=i("label",{class:"form-label"},"Neurological evaluation :",-1),Ne={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-1 gap-4"},Be=i("hr",{class:"border border-dashed my-2 md:my-4 xl:my-8"},null,-1),He=i("label",{class:"form-label"},"Life threatening condition in the past 24 hours :",-1),Me={key:0,class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-3 gap-4"},ze=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"monitoring"}," monitoring ",-1),We=i("hr",{class:"my-4 border-b border-accent"},null,-1),je={key:0},Qe={class:"mt-2 md:mt-4 xl:mt-8 space-y-2 md:space-y-4 lg:space-y-0 lg:grid grid-flow-col grid-cols-2 grid-rows-2 gap-4"},Ge=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"special-orders"}," special orders ",-1),Je=i("hr",{class:"my-4 border-b border-accent"},null,-1),Ke=E(" SUBMIT "),Xe=i("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"discussion"}," discussion ",-1),Ye=i("hr",{class:"my-4 border-b border-accent"},null,-1),rl={__name:"OrderEdit",props:{orderForm:{type:Object,required:!0},formConfigs:{type:Object,required:!0}},setup(p){const y=p,z=v(()=>w(()=>import("./HDForm.519cf994.js"),["assets/HDForm.519cf994.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/FormCheckbox.5b8c2adf.js","assets/FormInput.b47cd78a.js","assets/FormSelect.ebdade6a.js","assets/FormSelectOther.a6f04830.js","assets/ModalDialog.a74e84e5.js","assets/ModalDialog.7d4de9ea.css","assets/FormRadio.24801aa7.js","assets/IconCopy.b1fee944.js"])),W=v(()=>w(()=>import("./HFForm.1c8fe210.js"),["assets/HFForm.1c8fe210.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/FormCheckbox.5b8c2adf.js","assets/FormInput.b47cd78a.js","assets/FormSelect.ebdade6a.js","assets/FormRadio.24801aa7.js","assets/FormSelectOther.a6f04830.js","assets/ModalDialog.a74e84e5.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.b1fee944.js"])),j=v(()=>w(()=>import("./SLEDDForm.bf86cb0d.js"),["assets/SLEDDForm.bf86cb0d.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/FormCheckbox.5b8c2adf.js","assets/FormInput.b47cd78a.js","assets/FormTextarea.fded3dbe.js","assets/debounce.72e45d68.js","assets/FormSelect.ebdade6a.js","assets/FormRadio.24801aa7.js","assets/FormSelectOther.a6f04830.js","assets/ModalDialog.a74e84e5.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.b1fee944.js"])),Q=v(()=>w(()=>import("./TPEForm.b4c4834d.js"),["assets/TPEForm.b4c4834d.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/FormCheckbox.5b8c2adf.js","assets/FormInput.b47cd78a.js","assets/FormSelect.ebdade6a.js","assets/FormRadio.24801aa7.js","assets/FormSelectOther.a6f04830.js","assets/ModalDialog.a74e84e5.js","assets/ModalDialog.7d4de9ea.css","assets/IconCopy.b1fee944.js"])),G=v(()=>w(()=>import("./DialysisSlot.d4e1cc3b.js"),["assets/DialysisSlot.d4e1cc3b.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/WardSlot.c69e4e31.js","assets/IconUserMd.4aceb845.js"])),J=v(()=>w(()=>import("./WardSlot.c69e4e31.js").then(function(s){return s.W}),["assets/WardSlot.c69e4e31.js","assets/app.94c97313.js","assets/app.17b51d87.css","assets/IconUserMd.4aceb845.js"])),a=I({...y.formConfigs}),o=N({...y.orderForm});$(()=>o,s=>{s.hemodynamic.stable&&(s.hemodynamic.hypotension=!1,s.hemodynamic.inotropic_dependent=!1,s.hemodynamic.severe_hypertension=!1,s.hemodynamic.bradycardia=!1,s.hemodynamic.arrhythmia=!1),s.respiration.stable&&(s.respiration.hypoxia=!1,s.respiration.high_risk_airway_obstruction=!1),s.neurological.stable&&(s.neurological.gcs_drop=!1,s.neurological.drowsiness=!1),s.life_threatening_condition.stable&&(s.life_threatening_condition.acute_coronary_syndrome=!1,s.life_threatening_condition.cardiac_arrhythmia_with_hypotension=!1,s.life_threatening_condition.acute_ischemic_stroke=!1,s.life_threatening_condition.acute_ich=!1,s.life_threatening_condition.seizure=!1,s.life_threatening_condition.cardiac_arrest=!1),s.monitor.standard&&(s.monitor.ekg=!1,s.monitor.observe_chest_pain=!1,s.monitor.observe_neuro_sign=!1,s.monitor.other=null),C()},{deep:!0});const C=oe(function(){!a.can.update||window.axios.patch(a.endpoints.update,o.data()).catch(s=>{console.log(s)})},3e3),q=()=>o.patch(a.endpoints.submit,{onStart:()=>a.can.update=!1,onError:()=>a.can.update=!0});$(()=>T().props.value.event.fire,s=>{!s||T().props.value.event.name==="action-clicked"&&T().props.value.event.payload==="submit"&&H(q)});const P=S(!1),r=N({dialysis_type:a.dialysis_type,dialysis_at:a.dialysis_at,attending_staff:null,date_note:a.date_note,patient_type:null,swap_with:null}),K=S(null),g=I({slots:[],available:!1,reply:""}),D=()=>window.axios.post(a.endpoints.acute_hemodialysis_slot_available,{dialysis_type:r.dialysis_type,dialysis_at:r.dialysis_at,date_note:r.date_note}).then(s=>{g.slots=s.data.slots,g.available=s.data.available,g.reply=s.data.reply});Z(D),$(()=>r.date_note,s=>{!s||D()});const F=()=>{a.reserve_available_dates=y.formConfigs.reserve_available_dates,a.date_note=y.formConfigs.date_note,a.dialysis_type=y.formConfigs.dialysis_type,a.dialysis_at=y.formConfigs.dialysis_at,a.swap_code=y.formConfigs.swap_code,a.can=y.formConfigs.can},V=S(!1),O=S(!1),A=()=>{window.axios.patch(a.endpoints.copy).then(s=>{if(!s.data.found){O.value=!0,setTimeout(()=>O.value=!1,2e3);return}V.value=!0,H(()=>{s.data.records.map(t=>{o[t.type]=t.form})}),setTimeout(()=>V.value=!1,200)})};return(s,t)=>(d(),m(x,null,[n(ne,{class:"mb-4 md:mb-8",serology:p.formConfigs.serology},null,8,["serology"]),i("h2",_e,[pe,a.can.reschedule?(d(),m("button",{key:0,class:"flex items-center text-sm text-accent",onClick:t[0]||(t[0]=l=>P.value=!P.value)},[n(ce,{class:ee(["w-3 h-3 mr-1 transition-all transform duration-200 ease-out",{"rotate-180 text-accent-darker":P.value}])},null,8,["class"]),fe])):_("",!0)]),ye,i("div",ge,[n(U,{modelValue:a.hn,"onUpdate:modelValue":t[1]||(t[1]=l=>a.hn=l),name:"hn",label:"hn",readonly:!0},null,8,["modelValue"]),n(U,{modelValue:a.an,"onUpdate:modelValue":t[2]||(t[2]=l=>a.an=l),name:"an",label:"an",placeholder:"No active admission",readonly:!0},null,8,["modelValue"]),n(U,{modelValue:a.dialysis_at,"onUpdate:modelValue":t[3]||(t[3]=l=>a.dialysis_at=l),name:"dialysis_at",label:"dialysis at",readonly:!0},null,8,["modelValue"]),n(U,{modelValue:a.dialysis_type,"onUpdate:modelValue":t[4]||(t[4]=l=>a.dialysis_type=l),name:"dialysis_type",label:"dialysis type",readonly:!0},null,8,["modelValue"])]),n(f,{name:"slide-fade"},{default:u(()=>[P.value&&a.can.reschedule?(d(),m("div",be,[i("div",he,[n(ae,{label:"reschedule date",name:"date_note",modelValue:e(r).date_note,"onUpdate:modelValue":t[5]||(t[5]=l=>e(r).date_note=l),options:{enable:a.reserve_available_dates,inline:!0},ref_key:"dateNoteInput",ref:K},null,8,["modelValue","options"]),i("div",null,[e(r).dialysis_at.indexOf("Hemo")!==-1?(d(),h(e(G),{key:0,slots:g.slots},null,8,["slots"])):(d(),h(e(J),{key:1,slots:g.slots},null,8,["slots"])),g.reply&&!g.available?(d(),h(B,{key:2,class:"mt-4",type:"warning",title:"Cannot make a reservation",message:g.reply},null,8,["message"])):_("",!0)]),i("div",Ve,[n(L,{class:"block w-full text-center btn btn-accent",spin:e(r).processing,disabled:e(r).date_note===a.date_note||!g.available,onClick:t[6]||(t[6]=l=>e(r).date_note!==a.today?e(r).patch(a.endpoints.reschedule,{onFinish:F}):e(r).patch(a.endpoints.today_slot_request,{onFinish:F}))},{default:u(()=>[E(R(e(r).date_note===a.today||a.date_note===a.today?"REQUEST RESCHEDULE":"RESCHEDULE"),1)]),_:1},8,["spin","disabled"])])]),i("div",ve,[i("div",xe,[i("label",we,[Ce,n(le,{text:a.swap_code},{default:u(()=>[i("span",null,R(a.swap_code),1)]),_:1},8,["text"])]),n(U,{modelValue:e(r).swap_with,"onUpdate:modelValue":t[7]||(t[7]=l=>e(r).swap_with=l),name:"swap_with",label:"swap with"},null,8,["modelValue"]),n(L,{class:"block w-full text-center btn btn-accent",spin:e(r).processing,disabled:!e(r).swap_with||e(r).swap_with===a.swap_code,onClick:t[8]||(t[8]=l=>e(r).patch(a.endpoints.swap,{onFinish:F}))},{default:u(()=>[E(R(e(r).swap_with!==a.swap_code?"SWAP":"\u{1F644}\u{1F644}\u{1F644}"),1)]),_:1},8,["spin","disabled"])])])])):_("",!0)]),_:1}),n(f,{name:"slide-fade"},{default:u(()=>[O.value?(d(),h(B,{key:0,class:"mt-4 md:mt-8",message:"No previous order",title:"\u{1F605}",type:"warning"})):_("",!0)]),_:1}),n(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.hd!==void 0&&!V.value?(d(),m("div",ke,[n(e(z),{modelValue:e(o).hd,"onUpdate:modelValue":t[9]||(t[9]=l=>e(o).hd=l),"form-configs":p.formConfigs,onAutosave:e(C),onCopyPreviousOrder:A},null,8,["modelValue","form-configs","onAutosave"])])):_("",!0)]),_:1}),n(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.hf!==void 0&&!V.value?(d(),m("div",Ue,[n(e(W),{modelValue:e(o).hf,"onUpdate:modelValue":t[10]||(t[10]=l=>e(o).hf=l),"form-configs":p.formConfigs,onAutosave:e(C),onCopyPreviousOrder:A},null,8,["modelValue","form-configs","onAutosave"])])):_("",!0)]),_:1}),n(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.sledd!==void 0&&!V.value?(d(),m("div",Ee,[n(e(j),{modelValue:e(o).sledd,"onUpdate:modelValue":t[11]||(t[11]=l=>e(o).sledd=l),"form-configs":p.formConfigs,onAutosave:e(C),onCopyPreviousOrder:A},null,8,["modelValue","form-configs","onAutosave"])])):_("",!0)]),_:1}),n(f,{name:"slide-fade"},{default:u(()=>[p.orderForm.tpe!==void 0&&!V.value?(d(),m("div",Pe,[n(e(Q),{modelValue:e(o).tpe,"onUpdate:modelValue":t[12]||(t[12]=l=>e(o).tpe=l),"form-configs":p.formConfigs,onAutosave:e(C),onCopyPreviousOrder:A},null,8,["modelValue","form-configs","onAutosave"])])):_("",!0)]),_:1}),Ae,Se,Fe,n(c,{name:"hemodynamic.stable",modelValue:e(o).hemodynamic.stable,"onUpdate:modelValue":t[13]||(t[13]=l=>e(o).hemodynamic.stable=l),label:"Stable",toggler:!0,error:e(o).errors["hemodynamic.stable"]},null,8,["modelValue","error"]),n(f,{name:"slide-fade"},{default:u(()=>[e(o).hemodynamic.stable?_("",!0):(d(),m("div",Oe,[(d(!0),m(x,null,k(a.hemodynamic_symptoms,l=>(d(),h(c,{key:l.name,name:"hypotension",modelValue:e(o).hemodynamic[l.name],"onUpdate:modelValue":b=>e(o).hemodynamic[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),$e,Re,n(c,{name:"respiration.stable",modelValue:e(o).respiration.stable,"onUpdate:modelValue":t[14]||(t[14]=l=>e(o).respiration.stable=l),label:"Stable",toggler:!0,error:e(o).errors["respiration.stable"]},null,8,["modelValue","error"]),n(f,{name:"slide-fade"},{default:u(()=>[e(o).respiration.stable?_("",!0):(d(),m("div",Te,[(d(!0),m(x,null,k(a.respiration_options,l=>(d(),h(c,{key:l.name,name:"hypotension",modelValue:e(o).respiration[l.name],"onUpdate:modelValue":b=>e(o).respiration[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Le,qe,n(te,{modelValue:e(o).oxygen_support,"onUpdate:modelValue":t[15]||(t[15]=l=>e(o).oxygen_support=l),name:"oxygen_support",options:a.oxygen_options},null,8,["modelValue","options"]),De,Ie,n(c,{name:"neurological.stable",modelValue:e(o).neurological.stable,"onUpdate:modelValue":t[16]||(t[16]=l=>e(o).neurological.stable=l),label:"Stable",toggler:!0,error:e(o).errors["neurological.stable"]},null,8,["modelValue","error"]),n(f,{name:"slide-fade"},{default:u(()=>[e(o).neurological.stable?_("",!0):(d(),m("div",Ne,[(d(!0),m(x,null,k(a.neurological_options,l=>(d(),h(c,{key:l.name,name:"hypotension",modelValue:e(o).neurological[l.name],"onUpdate:modelValue":b=>e(o).neurological[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),Be,He,n(c,{name:"life_threatening_condition.stable",modelValue:e(o).life_threatening_condition.stable,"onUpdate:modelValue":t[17]||(t[17]=l=>e(o).life_threatening_condition.stable=l),label:"Stable",toggler:!0,error:e(o).errors["life_threatening_condition.stable"]},null,8,["modelValue","error"]),n(f,{name:"slide-fade"},{default:u(()=>[e(o).life_threatening_condition.stable?_("",!0):(d(),m("div",Me,[(d(!0),m(x,null,k(a.life_threatening_condition_options,l=>(d(),h(c,{key:l.name,name:"hypotension",modelValue:e(o).life_threatening_condition[l.name],"onUpdate:modelValue":b=>e(o).life_threatening_condition[l.name]=b,label:l.label},null,8,["modelValue","onUpdate:modelValue","label"]))),128))]))]),_:1}),ze,We,n(c,{name:"monitor.standard",modelValue:e(o).monitor.standard,"onUpdate:modelValue":t[18]||(t[18]=l=>e(o).monitor.standard=l),label:"Standard (MAP \u2265 65 mmHg)",toggler:!0,error:e(o).errors["monitor.standard"]},null,8,["modelValue","error"]),n(f,{name:"slide-fade"},{default:u(()=>[e(o).monitor.standard?_("",!0):(d(),m("div",je,[i("div",Qe,[(d(!0),m(x,null,k(a.monitors,(l,b)=>(d(),h(c,{key:b,label:l.label,name:l.name,modelValue:e(o).monitor[l.name],"onUpdate:modelValue":X=>e(o).monitor[l.name]=X},null,8,["label","name","modelValue","onUpdate:modelValue"]))),128))]),n(M,{class:"mt-2 md:mt-4 xl:mt-8",label:"other",placeholder:"others...",name:"monitoring_other",modelValue:e(o).monitor.other,"onUpdate:modelValue":t[19]||(t[19]=l=>e(o).monitor.other=l)},null,8,["modelValue"])]))]),_:1}),Ge,Je,n(c,{class:"mt-2 md:bt-4 xl:mt-8",name:"predialysis_labs_request",modelValue:e(o).predialysis_labs_request,"onUpdate:modelValue":t[20]||(t[20]=l=>e(o).predialysis_labs_request=l),label:"Predialysis Labs request",toggler:!0},null,8,["modelValue"]),n(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_bw",modelValue:e(o).postdialysis_bw,"onUpdate:modelValue":t[21]||(t[21]=l=>e(o).postdialysis_bw=l),label:"Postdialysis BW",toggler:!0},null,8,["modelValue"]),n(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_esa",modelValue:e(o).postdialysis_esa,"onUpdate:modelValue":t[22]||(t[22]=l=>e(o).postdialysis_esa=l),label:"Postdialysis ESA",toggler:!0},null,8,["modelValue"]),n(c,{class:"mt-2 md:mt-4 xl:mt-8",name:"postdialysis_iron_iv",modelValue:e(o).postdialysis_iron_iv,"onUpdate:modelValue":t[23]||(t[23]=l=>e(o).postdialysis_iron_iv=l),label:"Postdialysis Iron IV",toggler:!0},null,8,["modelValue"]),n(M,{class:"mt-2 md:mt-4 xl:mt-8",label:"treatments request",name:"treatments_request",modelValue:e(o).treatments_request,"onUpdate:modelValue":t[24]||(t[24]=l=>e(o).treatments_request=l),error:e(o).errors.treatments_request},null,8,["modelValue","error"]),n(L,{onClick:q,spin:e(o).processing,class:"mt-4 md:mt-8 w-full btn-accent"},{default:u(()=>[Ke]),_:1},8,["spin"]),Xe,Ye,n(se,{configs:a.comment},null,8,["configs"])],64))}};export{rl as default};
