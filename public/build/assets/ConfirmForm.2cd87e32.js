import{M as T}from"./ModalDialog.a74e84e5.js";import{_ as V}from"./SpinnerButton.22451709.js";import{_ as w}from"./FormInput.b47cd78a.js";import{r as e,o as _,q as b,b as y,w as s,a as c,t as g,i as C,a6 as M,d as N,n as q,a4 as m}from"./app.94c97313.js";const B={class:"font-semibold text-complement"},D={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-accent"},P=["innerHTML"],E=N(" Confirm "),F={__name:"ConfirmForm",setup(H,{expose:h}){const u=e(null),d=e(null),n=e(null),a=e(null),i=e(null),r=e(null);let f=null;const x=o=>{var l,t,v,p;u.value=(l=o.heading)!=null?l:"Please confirm",d.value=(t=o.confirmText)!=null?t:"Please confirm action or close to cancel",f=(v=o.confirmedEvent)!=null?v:"",n.value=(p=o.requireReason)!=null?p:!1,r.value.open(),n.value&&q(()=>i.value.focus())},k=()=>{m().props.value.event.payload=a.value,m().props.value.event.name=f,m().props.value.event.fire=+new Date,r.value.close(),a.value=null};return h({open:x}),(o,l)=>(_(),b(M,{to:"body"},[y(T,{"width-mode":"form-cols-1",ref_key:"modal",ref:r},{header:s(()=>[c("div",B,g(u.value),1)]),body:s(()=>[c("div",D,[c("p",{class:"font-semibold text-yellow-400",innerHTML:d.value},null,8,P),n.value?(_(),b(w,{key:0,class:"mt-4 md:mt-6",modelValue:a.value,"onUpdate:modelValue":l[0]||(l[0]=t=>a.value=t),placeholder:"reason",name:"reason",ref_key:"reasonInput",ref:i},null,8,["modelValue"])):C("",!0)])]),footer:s(()=>[y(V,{class:"btn btn-accent w-full mt-6",onClick:k,disabled:n.value&&!a.value},{default:s(()=>[E]),_:1},8,["disabled"])]),_:1},512)]))}};export{F as default};
