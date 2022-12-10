import{_ as x}from"./FormInput.8fdf7c5d.js";import{_ as y}from"./SpinnerButton.36acba4a.js";import{M as h}from"./ModalDialog.0dba2dc7.js";import{s as T,r as i,o as m,q as d,b as f,w as r,a as t,t as g,i as k,d as q,a9 as V,n as v}from"./app.a2148d35.js";const C={class:"font-semibold text-complement"},R={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent"},w=["innerHTML"],H={__name:"ConfirmFormComposable",emits:["confirmed"],setup(M,{expose:u,emit:_}){const e=T({heading:"Please confirm",confirmText:"Please confirm action or close to cancel",requireReason:!1,reason:null}),l=i(null),s=i(null),p=a=>{var o,n,c;e.heading=(o=a.heading)!=null?o:"Please confirm",e.confirmText=(n=a.confirmText)!=null?n:"Please confirm action or close to cancel",e.requireReason=(c=a.requireReason)!=null?c:!1,e.reason=null,s.value.open(),e.requireReason&&v(()=>l.value.focus())},b=()=>{_("confirmed",e.reason),s.value.close()};return u({open:p}),(a,o)=>(m(),d(V,{to:"body"},[f(h,{"width-mode":"form-cols-1",ref_key:"modal",ref:s},{header:r(()=>[t("div",C,g(e.heading),1)]),body:r(()=>[t("div",R,[t("p",{class:"font-semibold text-yellow-400",innerHTML:e.confirmText},null,8,w),e.requireReason?(m(),d(x,{key:0,class:"mt-4 md:mt-6",modelValue:e.reason,"onUpdate:modelValue":o[0]||(o[0]=n=>e.reason=n),placeholder:"reason",name:"reason",ref_key:"reasonInput",ref:l},null,8,["modelValue"])):k("",!0)])]),footer:r(()=>[f(y,{class:"btn btn-accent w-full mt-6",onClick:b,disabled:e.requireReason&&!e.reason},{default:r(()=>[q(" Confirm ")]),_:1},8,["disabled"])]),_:1},512)]))}};export{H as default};
