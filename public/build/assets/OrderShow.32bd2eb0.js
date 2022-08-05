import{h as V,o as s,c as r,d as u,w as g,T as S,b as l,F as c,v as _,s as o,u as n,k as m,S as $,t as F,e as x}from"./app.6b6092b7.js";import{F as w,_ as C}from"./FallbackSpinner.218353ff.js";import{_ as b}from"./DisplayData.83743af8.js";import{_ as h}from"./SpinnerButton.8b913ee6.js";import{_ as y}from"./FormCheckbox.348378dd.js";import{_ as k}from"./FormDatetime.ad892f07.js";import{_ as q}from"./CommentSection.1c102ac8.js";import"./FormTextarea.2deadbec.js";import"./debounce.c9cbbdff.js";const P=l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement"}," Reservation data ",-1),U=l("hr",{class:"my-4 border-b border-accent"},null,-1),B={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},E=l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"special-requests"}," Special Requests ",-1),N=l("hr",{class:"my-4 border-b border-accent"},null,-1),O={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},T=l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"predialysis-evaluation"}," Predialysis Evaluation ",-1),j=l("hr",{class:"my-4 border-b border-accent"},null,-1),D={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},R={class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"prescription"},A=l("hr",{class:"my-4 border-b border-accent"},null,-1),L={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},z=l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"session-data"}," session data ",-1),G=l("hr",{class:"my-4 border-b border-accent"},null,-1),H={class:"grid gap-2 md:gap-4 md:grid-cols-2 xl:gap-8 2xl:grid-cols-4"},I=x(" UPDATE "),J={key:0,class:"border-t-2 border-complement border-dashed md:border-t-0 pt-4 md:pt-0"},K=x(" Start Session "),M=x(" Finish Session "),Q=l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement scroll-mt-16 md:scroll-mt-8",id:"discussion"}," discussion ",-1),W=l("hr",{class:"my-4 border-b border-accent"},null,-1),ie={__name:"OrderShow",props:{configs:{type:Object,required:!0},content:{type:Object,required:!0}},setup(t){const f=t,a=V({dialysis_at_chronic_unit:f.configs.session.dialysis_at_chronic_unit,extra_slot:f.configs.session.extra_slot,started_at:f.configs.session.started_at,finished_at:f.configs.session.finished_at,hashed_key:f.configs.session.hashed_key});return(p,i)=>(s(),r(c,null,[u(S,{mode:"out-in"},{default:g(()=>[t.configs.covid.hn?(s(),o($,{key:0},{fallback:g(()=>[u(w)]),default:g(()=>[u(C,{class:"mb-4",configs:t.configs.covid},null,8,["configs"])]),_:1})):m("",!0)]),_:1}),P,U,l("div",B,[(s(!0),r(c,null,_(Object.keys(t.content.reservation),(e,d)=>(s(),o(b,{key:d,label:e,data:t.content.reservation[e]},null,8,["label","data"]))),128))]),E,N,l("div",O,[(s(!0),r(c,null,_(t.content.special_requests.filter(e=>e.data),(e,d)=>(s(),o(b,{key:d,label:e.label,data:e.data},null,8,["label","data"]))),128))]),T,j,l("div",D,[(s(!0),r(c,null,_(t.content.predialysis_evaluation.filter(e=>e.data),(e,d)=>(s(),o(b,{key:d,label:e.label,data:e.data},null,8,["label","data"]))),128))]),(s(),r(c,null,_(["hd","hf","tpe","sledd"],e=>(s(),r(c,{key:e},[t.content[e]!==void 0?(s(),r(c,{key:0},[l("h2",R,F(e)+" Prescription ",1),A,l("div",L,[(s(!0),r(c,null,_(t.content[e],(d,v)=>(s(),o(b,{key:v,label:d.label,data:d.data},null,8,["label","data"]))),128))])],64)):m("",!0)],64))),64)),z,G,l("div",H,[l("div",null,[t.configs.can.check_dialysis_at_chronic_unit?(s(),o(y,{key:0,toggler:!0,label:"Dialysis at Chronic unit",name:"dialysis_at_chronic_unit",modelValue:n(a).dialysis_at_chronic_unit,"onUpdate:modelValue":i[0]||(i[0]=e=>n(a).dialysis_at_chronic_unit=e)},null,8,["modelValue"])):m("",!0),t.configs.can.change_session_data?(s(),o(y,{key:1,class:"mt-2 md:mt-4",toggler:!0,label:"Extra slot",name:"extra_slot",modelValue:n(a).extra_slot,"onUpdate:modelValue":i[1]||(i[1]=e=>n(a).extra_slot=e)},null,8,["modelValue"])):m("",!0),u(k,{class:"mt-2 md:mt-4",mode:"time",label:"started at",modelValue:n(a).started_at,"onUpdate:modelValue":i[2]||(i[2]=e=>n(a).started_at=e),name:"started_at",disabled:!t.configs.can.edit_timestamp},null,8,["modelValue","disabled"]),u(k,{class:"mt-2 md:mt-4",mode:"time",label:"finished at",modelValue:n(a).finished_at,"onUpdate:modelValue":i[3]||(i[3]=e=>n(a).finished_at=e),name:"finished_at",disabled:!t.configs.can.edit_timestamp},null,8,["modelValue","disabled"]),t.configs.can.change_session_data?(s(),o(h,{key:2,class:"mt-2 md:mt-4 btn btn-complement w-full",spin:n(a).processing,onClick:i[4]||(i[4]=e=>{n(a).patch(t.configs.routes.update_session,{preserveScroll:!1,preserveState:!0,onFinish:()=>{n(a).processing=!1}})})},{default:g(()=>[I]),_:1},8,["spin"])):m("",!0)]),t.configs.can.start_session||t.configs.can.finish_session?(s(),r("div",J,[t.configs.can.start_session?(s(),o(h,{key:0,class:"btn btn-accent w-full",onClick:i[5]||(i[5]=e=>{p.$inertia.post(t.configs.routes.start_session,{},{preserveScroll:!0,preserveState:!0,onProgress:()=>{n(a).processing=!0},onFinish:()=>{n(a).processing=!1}})}),spin:n(a).processing},{default:g(()=>[K]),_:1},8,["spin"])):t.configs.can.finish_session?(s(),o(h,{key:1,class:"btn btn-accent w-full",onClick:i[6]||(i[6]=e=>{p.$inertia.post(t.configs.routes.finish_session,{_method:"delete"},{preserveScroll:!0,preserveState:!0,onProgress:()=>{n(a).processing=!0},onFinish:()=>{n(a).processing=!1}})}),spin:n(a).processing},{default:g(()=>[M]),_:1},8,["spin"])):m("",!0)])):m("",!0)]),Q,W,u(q,{configs:t.configs.comment},null,8,["configs"])],64))}};export{ie as default};
