import{_ as b}from"./FormInput-084b806e.js";import{_ as x}from"./SpinnerButton-91e436ba.js";import{M as h}from"./ModalDialog-a959e4f2.js";import{l as y,r as l,b as c,k as i,e as m,f as a,d as r,t as T,h as g,g as k,D as V,n as q}from"./app-4c4070f2.js";const v={class:"font-semibold text-complement"},C={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent"},R=["innerHTML"],D={__name:"ConfirmFormComposable",emits:["confirmed"],setup(w,{expose:d,emit:f}){const e=y({heading:"Please confirm",confirmText:"Please confirm action or close to cancel",requireReason:!1,reason:null}),s=l(null),n=l(null),u=o=>{e.heading=o.heading??"Please confirm",e.confirmText=o.confirmText??"Please confirm action or close to cancel",e.requireReason=o.requireReason??!1,e.reason=null,n.value.open(),e.requireReason&&q(()=>s.value.focus())},_=()=>{f("confirmed",e.reason),n.value.close()};return d({open:u}),(o,t)=>(c(),i(V,{to:"body"},[m(h,{"width-mode":"form-cols-1",ref_key:"modal",ref:n},{header:a(()=>[r("div",v,T(e.heading),1)]),body:a(()=>[r("div",C,[r("p",{class:"font-semibold text-yellow-400",innerHTML:e.confirmText},null,8,R),e.requireReason?(c(),i(b,{key:0,class:"mt-4 md:mt-6",modelValue:e.reason,"onUpdate:modelValue":t[0]||(t[0]=p=>e.reason=p),placeholder:"reason",name:"reason",ref_key:"reasonInput",ref:s},null,8,["modelValue"])):g("",!0)])]),footer:a(()=>[m(x,{class:"btn btn-accent w-full mt-6",onClick:_,disabled:e.requireReason&&!e.reason},{default:a(()=>[k(" Confirm ")]),_:1},8,["disabled"])]),_:1},512)]))}};export{D as default};
