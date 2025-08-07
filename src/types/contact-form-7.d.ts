export {};

declare global {
  interface Window {
    wpcf7?: {
      apiSettings?: {
        root: string;
        namespace: string;
      };
      cached?: number;
      init?: (form: HTMLElement | null) => void;
    };
  }
}
