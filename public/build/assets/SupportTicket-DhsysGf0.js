import{T as f,b as o,c as d,e as r,u as a,d as s,F as c,m as u,t as m}from"./app-CC1FPugz.js";import{_ as b}from"./FormTextarea-Dvb3ffAu.js";import{_ as p}from"./PaginationNav-O8wjWSEn.js";const k=["disabled"],_={class:"my-4 md:my-8 grid gap-2 md:gap-4"},x=["textContent"],g=["textContent"],C={__name:"SupportTicket",props:{feedback:{type:Object,required:!0},configs:{type:Object,required:!0}},setup(l){const e=f({feedback:null});return(y,n)=>(o(),d(c,null,[r(b,{name:"form.feedback",label:"feedback",placeholder:"Please feel free to leave an anonymous feedback 🙃",modelValue:a(e).feedback,"onUpdate:modelValue":n[0]||(n[0]=t=>a(e).feedback=t)},null,8,["modelValue"]),s("button",{class:"mt-4 md:mt8 btn btn-accent w-full md:w-1/2",onClick:n[1]||(n[1]=t=>a(e).post(l.configs.store_endpoint,{preserveState:!1})),disabled:!a(e).feedback||a(e).processing}," SEND ",8,k),s("div",_,[(o(!0),d(c,null,u(l.feedback.data,(t,i)=>(o(),d("div",{key:i,class:"bg-primary-darker rounded-xl shadow-sm p-2 md:p-4"},[s("pre",{class:"font-sarabun",textContent:m(t.feedback)},null,8,x),s("small",{class:"mt-2 md:mt-4 block w-full text-right text-xs italic text-complement",textContent:m(t.when)},null,8,g)]))),128))]),r(p,{links:l.feedback.links},null,8,["links"])],64))}};export{C as default};
