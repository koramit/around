import{_ as x}from"./FormInput-CnL0NmD-.js";import{_ as y}from"./SpinnerButton-Cspc9jAI.js";import{M as h}from"./ModalDialog-CLl09q7z.js";import{k as T,r as l,h as i,j as c,d as m,e as n,b as s,t as g,g as k,f as V,B as q,n as v}from"./app-EyHLH-0t.js";const C={class:"font-semibold text-complement"},R={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent"},w=["innerHTML"],H={__name:"ConfirmFormComposable",emits:["confirmed"],setup(B,{expose:d,emit:f}){const u=f,e=T({heading:"Please confirm",confirmText:"Please confirm action or close to cancel",requireReason:!1,reason:null}),t=l(null),r=l(null),p=o=>{e.heading=o.heading??"Please confirm",e.confirmText=o.confirmText??"Please confirm action or close to cancel",e.requireReason=o.requireReason??!1,e.reason=null,r.value.open(),e.requireReason&&v(()=>t.value.focus())},_=()=>{u("confirmed",e.reason),r.value.close()};return d({open:p}),(o,a)=>(i(),c(q,{to:"body"},[m(h,{"width-mode":"form-cols-1",ref_key:"modal",ref:r},{header:n(()=>[s("div",C,g(e.heading),1)]),body:n(()=>[s("div",R,[s("p",{class:"font-semibold text-yellow-400",innerHTML:e.confirmText},null,8,w),e.requireReason?(i(),c(x,{key:0,class:"mt-4 md:mt-6",modelValue:e.reason,"onUpdate:modelValue":a[0]||(a[0]=b=>e.reason=b),placeholder:"reason",name:"reason",ref_key:"reasonInput",ref:t},null,8,["modelValue"])):k("",!0)])]),footer:n(()=>[m(y,{class:"btn btn-accent w-full mt-6",onClick:_,disabled:e.requireReason&&!e.reason},{default:n(()=>a[1]||(a[1]=[V(" Confirm ")])),_:1},8,["disabled"])]),_:1},512)]))}};export{H as default};